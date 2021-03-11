import React from 'react';
import axios from "axios";

class Row extends React.Component {
    constructor(props) {
        super(props);
        this.update = this.update.bind(this);
        this.state = {
            inService: false,
        }
    }

    async update() {

        if(this.state.inService) {
            this.setState({inService: false})
        }else{
            this.setState({inService: true})
        }
        await axios({
            url: '/data/gestion/service/setbyadmin/' + this.props.userid,
            method: 'GET',
        })

    }

    componentDidMount() {
        if(this.props.inService === 1){
            this.setState({inService: true})
        }else{
            this.setState({inService: false})
        }
    }

    render() {
        return (
            <div className={'row'}>
                <div className={'cell column-1'}>
                    <p>{this.props.name}</p>
                </div>
                <div className={'cell column-2'}>
                    <p>{this.props.lundi}</p>
                </div>
                <div className={'cell column-3'}>
                    <p>{this.props.mardi}</p>
                </div>
                <div className={'cell column-4'}>
                    <p>{this.props.mercredi}</p>
                </div>
                <div className={'cell column-5'}>
                    <p>{this.props.jeudi}</p>
                </div>
                <div className={'cell column-6'}>
                    <p>{this.props.vendredi}</p>
                </div>
                <div className={'cell column-7'}>
                    <p>{this.props.samedi}</p>
                </div>
                <div className={'cell column-8'}>
                    <p>{this.props.dimanche}</p>
                </div>
                <div className={'cell column-9'}>
                    <p>{this.props.total}</p>
                </div>
                <div className={'cell en service'}>
                    <div className={'switch-container'}>
                        <input id={"switch"+this.props.itemid} checked={this.state.inService} className="payed_switch" type="checkbox" onChange={(e) => {this.update()}}/>
                        <label htmlFor={"switch"+this.props.itemid} className={"payed_switchLabel"}/>
                    </div>
                </div>
            </div>
        )
    }
}

export default Row;
