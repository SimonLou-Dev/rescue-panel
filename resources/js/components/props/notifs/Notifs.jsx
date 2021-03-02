import React from 'react';
import axios from "axios";


class notifs extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            time: 100,
            type: '',
            info: '',
        }
    }

    componentDidMount() {
        this.color();
        this.timerID = setInterval(
            () => this.tick(),
            5
        );
    }
    componentWillUnmount() {
        clearInterval(this.timerID);
    }
    tick() {
        this.color();
        this.setState({time: this.state.time-0.2})
        if(this.state.time < 1){
            this.props.remove(this.props.id)
            clearInterval(this.timerID);
        }
    }
    color(){
        if (this.props.type === 1){
            this.setState({type: 'success', 'info': 'Succès !'})
        }
        if (this.props.type === 2){
            this.setState({type: 'warning', 'info': 'Attention !'})
        }
        if (this.props.type === 3){
            this.setState({type: 'danger', 'info': 'Erreur !'})
        }
    }

    render() {
        return (
            <div className={"notifications "+ this.state.type} id={this.props.info}>
                <div className={'notif-header'}>
                    <h3>{this.state.info}</h3>
                    <button onClick={()=> this.props.remove(this.props.id)}>X</button>
                </div>
                <div className={'notif-separator'}/>
                <div className={'notif-content'}>
                    <p>{this.props.raison}</p>
                </div>
                <div className={'progress-bar'}>
                    <div style={{width: this.state.time+'%'}} className={'progress'}/>
                </div>
            </div>
        )
    }
}

export default notifs;
