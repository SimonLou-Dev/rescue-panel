import React from 'react';
import {Link} from "react-router-dom";


class GetInfos extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            live: 1,
            tel: "",
            compte: "",
            timezone: 1,
            liveempty: false,
            telempty: false,
            compteempty: false,
            timezoneempty:false
        }
        this.sendinfos = this.sendinfos.bind(this)
    }

    async sendinfos(e) {
        e.preventDefault();
        let errore = false;
        if (this.state.tel < 1) {
            this.setState({telempty: true})
            errore = true;
        }
        if (this.state.compte < 1) {
            this.setState({compteempty: true})
            errore = true;
        }
        if (this.state.timezone === 1) {
            this.setState({timezoneempty: true})
            errore = true;
        }
        if (this.state.live === 1) {
            this.setState({liveempty: true})
            errore = true;
        }
        if (!errore) {
            let req = await axios({
                method: 'post',
                url: '/data/postuserinfos',
                data: {
                    'living': this.state.live,
                    'timezone': this.state.timezone,
                    'tel': this.state.tel,
                    'compte': this.state.compte,
                }
            })
            if (req.status === 201) {
                window.location.href = "/";
            }
        }
    }

    componentDidMount() {
        setTimeout(() => {
            if(this.state.tel < 8 ){
                this.setState({telempty:true})
            }else{
                this.setState({telempty:false})
            }
            if(this.state.compte < 4 ){
                this.setState({compteempty:true})
            }
            else{
                this.setState({compteempty:false})
            }
        },500);
    }

    render() {
        return (
            <div className={'Register'}>
                <div className={'Form'}>
                    <form method={'POST'} onSubmit={this.sendinfos}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                        <h1>Informations</h1>
                        <label>Contré habité : </label>
                        <select defaultValue={this.state.live} onChange={(e)=>this.setState({live:e.target.value}) }>
                            <option value={1} disabled>choisir</option>
                            <option>LS</option>
                            <option>BC</option>
                        </select>
                        {this.state.liveempty &&
                        <div className={'form-error'}>
                            <p>Pas de numéros de téléphone (min 8 caractères)</p>
                        </div>
                        }
                        <label>n° de tel IG : </label>
                        <input type={'number'} value={this.state.tel} onChange={(e)=>{this.setState({tel:e.target.value}); this.componentDidMount()}}/>
                        {this.state.telempty &&
                        <div className={'form-error'}>
                            <p>Pas de numéros de téléphone (min 8 caractères)</p>
                        </div>
                        }
                        <label>n° de compte </label>
                        <input type={'number'} value={this.state.compte} name={'psw'} onChange={(e)=>{this.setState({compte:e.target.value}); this.componentDidMount()}}/>
                        {this.state.compteempty &&
                        <div className={'form-error'}>
                            <p>Pas de numéros de compte</p>
                        </div>
                        }
                        <label>Fuseau horaire : </label>
                        <select  defaultValue={this.state.timezone} name={'psw_repeat'} onChange={(e)=>this.setState({timezone:e.target.value})}>
                            <option value={1} disabled>choisir</option>
                            <option>[FR] Paris - GMT+1</option>
                            <option>[NY] New York - UTC-5</option>
                        </select>
                        {this.state.timezoneempty &&
                        <div className={'form-error'}>
                            <p>Choisier une time zone</p>
                        </div>
                        }
                        <div className={'btn-contain'}>
                            <button type={'submit'} className={'btn'}>Terminer</button>
                        </div>
                    </form>
                </div>
            </div>
        )
    };
}

export default GetInfos;
