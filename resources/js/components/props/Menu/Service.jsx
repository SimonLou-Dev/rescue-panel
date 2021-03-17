import React from "react";
import axios from 'axios';


class Service extends React.Component{

    constructor(props) {
        super(props);
        this.state= {
            disabled: false,
        };
        this.OnServiceCliked = this.OnServiceCliked.bind(this);
        this.OffServiceClicked = this.OffServiceClicked.bind(this);
        this.cooldown = this.cooldown.bind(this);
        this.state = {
            timeremain: 2*60,
        }
    }

    cooldown(){
        this.setState({timeremain: this.state.timeremain-1});
    }

    componentWillUnmount() {
        clearInterval(this.timerID);
    }

    OffServiceClicked() {
        this.setState(state => ({status: true,disabled: true}));

        this.timerID = setInterval(
            () => this.cooldown(),
            1000
        );
        setTimeout(()=>{
            this.setState({disabled:false});
        },2*60*1000)
        this.requestDB().then(r => this.props.serviceUpade(true));
    }

    async requestDB() {
        await axios({
            method: "PUT",
            url: '/data/setstatus',
        });

    }

    OnServiceCliked() {
        this.setState(state => ({status: false,disabled: true}));

        this.timerID = setInterval(
            () => this.cooldown(),
            1000
        );
        setTimeout(()=>{
            this.setState({disabled:false});
        },2*60*1000);
        this.requestDB().then(r => this.props.serviceUpade(false));
    }

    async componentDidMount() {
        var req = await axios({
            method: "GET",
            url: '/data/getstatus',
        });
        if(req.data.service === 1){
            this.setState({status: true})
            this.props.serviceUpade(true)
        }else{
            this.setState({status: false})
            this.props.serviceUpade(false)
        }

    }

    render() {
        if (this.state.disabled){
            return (
                <button type={"button"} disabled={this.state.disabled ? 'disabled' : null} id='service' className="OffService" onClick={this.OffServiceClicked}>
                    {this.state.timeremain} sec
                </button>
            );
        }else {
            if (this.state['status']) {
                return (

                    <button type={"button"} disabled={this.state.disabled ? 'disabled' : null} id='service'
                            className="OnService" onClick={this.OnServiceCliked}>
                        En service
                    </button>);
            } else if (!this.state['status']) {
                return (
                    <button type={"button"} disabled={this.state.disabled ? 'disabled' : null} id='service'
                            className="OffService" onClick={this.OffServiceClicked}>
                        Hors Service
                    </button>
                );
            }
        }

    }

}
export default Service;

