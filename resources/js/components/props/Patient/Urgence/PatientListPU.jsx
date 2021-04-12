import React from 'react';
import {Redirect} from "react-router-dom";
import axios from "axios";

class PatientListPU extends React.Component {
    constructor(props) {
        super(props);
        this.state =({
            redirect: false,
        })
    }

    render() {
        console.log(this.props)
        if(this.state.redirect){
            return (
                <Redirect to={this.state.redirect}/>
            )
        }else {
            return (
                <div className={'Patient-list-card'}>
                    {this.props.idcard === true || this.props.idcard === 1&&
                    <h5 className={'id'}>[ID]</h5>
                    }
                    <h5 className={'name'}>{this.props.name}</h5>
                    <h5 className={'date'}>[{this.props.date}]</h5>
                    <h5 className={'color'}>{this.props.color}</h5>

                    <button onClick={(e)=> {
                        this.setState({redirect: '/patient/dossiers?id='+this.props.urlid})
                    }} className={'edit'}><img src={ '/assets/images/editer.png'} alt={''}/></button>

                    <button onClick={async (e) => {
                        var req = await axios({
                            method: 'DELETE',
                            url: '/data/blackcode/delete/patient/'+this.props.urlid
                        })
                        this.props.update();
                    }} className={'delete'}><img src={'/assets/images/cancel.png'} alt={''}/></button>
                </div>
            )
        }
    };
}

export default PatientListPU;
