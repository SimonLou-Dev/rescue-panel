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
        if(this.state.redirect){
            return (
                <Redirect to={this.state.redirect}/>
            )
        }else {
            return (
                <div className={'Patient-list-card'}>
                    <div className={'text'}>
                        <h5 className={'name'}>{this.props.name}</h5>
                        <h5 className={'date'}>[{this.props.date}]</h5>
                    </div>
                    <div className="btn-container">
                        <button onClick={(e)=> {
                            this.setState({redirect: '/patient/dossiers?id='+this.props.urlid})
                        }}><img src={ '/assets/images/editer.png'} alt={''}/></button>
                        <button onClick={async (e) => {
                            var req = await axios({
                                method: 'DELETE',
                                url: '/data/pu/removepatient/'+this.props.urlid
                            })
                            this.props.update();
                        }}><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                </div>
            )
        }
    };
}

export default PatientListPU;
