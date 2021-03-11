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

    async OffServiceClicked() {
        this.setState(state => ({status: true,disabled: true}));
        await axios({
            method: "GET",
            url: '/data/setstatus/',
        });
        this.timerID = setInterval(
            () => this.cooldown(),
            1000
        );
        setTimeout(()=>{
            this.setState({disabled:false});
        },2*60*1000)
    }

    async OnServiceCliked() {
        this.setState(state => ({status: false,disabled: true}));
        await axios({
            method: "GET",
            url: '/data/setstatus/',
        });
        this.timerID = setInterval(
            () => this.cooldown(),
            1000
        );
        setTimeout(()=>{
            this.setState({disabled:false});
        },2*60*1000);
    }

    async componentDidMount() {

        var req = await axios({
            method: "GET",
            url: '/data/getstatus',
        });
        if(req.data.service){
            this.setState({status: true})
        }else{
            this.setState({status: false})
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

