import React from 'react';
import UrgenceActive from "../props/Patient/Urgence/Actif/UrgenceActive";
import UrgenceDisabled from "../props/Patient/Urgence/Inactif/UrgenceDisabled";
import axios from "axios";
export const rootUrl = document.querySelector('body').getAttribute('data-root-url');
class Urgence extends React.Component {
    constructor(props) {
        super(props);
        this.state = {activate: false, p:0, data: false};
        this.ModifyActivityState = this.ModifyActivityState.bind(this);
        this.update = this.update.bind(this);
        this.hasdata = this.hasdata.bind(this);
    }

    async ModifyActivityState(type, place) {
        this.hasdata(false);
        if (this.state.activate) {
            var req = await axios({
                method: 'POST',
                url: '/data/pu/setstate/false',
            })
            this.setState({activate: false});
        } else {
            var req = await axios({
                method: 'POST',
                url: '/data/pu/setstate/true',
                data: {
                    type: type,
                    place: place,
                }
            })
            if(req.data.status === 201){
                this.setState({activate: true});
            }else{
                this.update();
            }

        }
        this.hasdata(true);
    }

    componentDidMount() {
        this.update();
    }

    hasdata(bool){
        this.setState({data:bool});
    }

    async update() {
        this.hasdata(false);
        var req = await axios({
            url: '/data/pu/getstate',
            method: 'GET'
        });
        var res = await axios({
            url: '/data/pu/isInPu',
            method: 'GET'
        })
        this.setState({p: res.data.p, activate: req.data.state});
        this.hasdata(true);
    }

    render() {
        if(this.state.data){
            if(this.state.activate){
                if(this.state.p === 1){
                    return(
                        <UrgenceActive rootUrl={rootUrl} ChangeState={this.ModifyActivityState} upload={this.update}/>
                    )
                }else{
                    return (
                        <div className={'PU-non-Participant'}>
                            <div className={'card'}>
                                <h1>Voulez-vous participer au black code actuel ?</h1>
                                <button onClick={async () => {
                                    this.setState({p: 1})
                                    var req = await axios({
                                        url: '/data/pu/addtopu',
                                        method: 'GET'
                                    })
                                }} className={'btn'}>Oui</button>
                            </div>
                        </div>
                    )
                }

            }else{
                return (
                    <UrgenceDisabled rootUrl={rootUrl} ChangeState={this.ModifyActivityState} />
                )
            }
        }else{
            return (
                !this.state.data &&
                    <div className={'load'}>
                <img src={'/assets/images/loading.svg'} alt={''}/>
            </div>

            )
        }

    };
}

export default Urgence;
