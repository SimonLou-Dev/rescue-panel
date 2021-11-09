import React from 'react';
import PermsContext from "../../context/PermsContext";
import axios from "axios";


class PersonnelCard extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            moused: false
        }

    }

    render() {
        let perm = this.context
        return(
            <div className={"Personnel-card " + (this.props.selected ?  'selected' : '')}  onClick={()=>{
                    this.props.clicked(this.props.user.id)
            }}>
                <h5>{this.props.name}</h5>

                {this.props.user.get_service_state !== null &&
                    <div className={'tag'} style={{backgroundColor: this.props.user.get_service_state.color}}/>
                }
            </div>
        );
    }
}
export default PersonnelCard;
PersonnelCard.contextType = PermsContext;
