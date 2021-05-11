import React from 'react';
import PermsContext from "../../context/PermsContext";


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
            <div className={"Personnel-card"} onMouseEnter={()=>{
                this.setState({moused:true})
            }} onMouseLeave={()=>{
                this.setState({moused:false})
            }}>
                <h5>{this.props.name}</h5>
                <div className={'tag'} style={{backgroundColor: this.props.color}}/>
                {this.state.moused &&
                    <div className={'tag-selector'}>
                        <button>
                            <label>Formations</label>
                            <div className={'tag'} style={{backgroundColor: '#eb34eb'}}/>
                        </button>
                        <button>
                            <label>Formations</label>
                            <div className={'tag'} style={{backgroundColor: '#eb34eb'}}/>
                        </button>
                        <button>
                            <label>Formations</label>
                            <div className={'tag'} style={{backgroundColor: '#eb34eb'}}/>
                        </button>
                        <button>
                            <label>N/A</label>
                            <div className={'tag'} style={{backgroundColor: '#303030'}}/>
                        </button>
                    </div>
                }
            </div>
        );
    }
}
export default PersonnelCard;
PersonnelCard.contextType = PermsContext;
