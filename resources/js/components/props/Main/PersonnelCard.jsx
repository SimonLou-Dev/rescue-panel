import React from 'react';
import PermsContext from "../../context/PermsContext";
import axios from "axios";


class PersonnelCard extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            moused: false
        }
        this.BtnClick = this.BtnClick.bind(this);
    }

    async BtnClick(id) {
        await axios({
            method: 'PUT',
            url: '/data/user/' + this.props.user.id + '/changestate/' + id,
        }).then(() =>{
            this.props.update();
        })

    }

    render() {
        let perm = this.context
        return(
            <div className={"Personnel-card"} onMouseEnter={()=>{
                this.setState({moused:true})
            }} onMouseLeave={()=>{
                this.setState({moused:false})
            }}>
                <h5>{this.props.name}</h5>

                {this.props.user.get_service_state !== null &&
                    <div className={'tag'} style={{backgroundColor: this.props.user.get_service_state.color}}/>
                }

                {this.state.moused && (perm.service_modify === 1 || perm.user_id === this.props.user.id) &&
                    <div className={'tag-selector'}>
                        {this.props.states && this.props.states.map((item)=>
                            <button onClick={()=>{this.BtnClick(item.id);}} key={item.id}>
                                <label>{item.name}</label>
                                <div className={'tag'} style={{backgroundColor:item.color}}/>
                            </button>
                        )}
                        <button onClick={()=>{this.BtnClick(null);}}>
                            <label>N/A</label>
                            <div className={'tag'}/>
                        </button>
                    </div>
                }
            </div>
        );
    }
}
export default PersonnelCard;
PersonnelCard.contextType = PermsContext;
