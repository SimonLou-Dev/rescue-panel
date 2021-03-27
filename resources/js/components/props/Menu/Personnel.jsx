import React from "react";
import {NavLink} from "react-router-dom";

class Personnel extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            facture:false,
            vols:true,
        }
    }


    componentDidUpdate(prevProps, prevState, snapshot) {

        if(this.props.service !== prevProps.service){
            if(this.props.service === true){
                if(this.props.user.pilote){
                    this.setState({vols:true})
                }else{
                    this.setState({vols:false})
                }
                this.setState({facture:true})
            }
        }

        if(this.props.perm !== prevProps.perm){
            if(!this.props.service){
                if(this.props.perm['HS_facture']){
                    this.setState({facture:true})
                }else{
                    this.setState({facture:false})
                }
            }
        }

        if(this.props.user !== prevProps.user){
            if(this.props.user.pilote){
                this.setState({vols:true})
            }else{
                this.setState({vols:false})
            }
        }
    }

    render() {
        return (
            <div className="Menu-Item" id="Service">
                <h2 className="Menu_title"><span>Personnel</span></h2>
                <ul className="Menu-list">
                    <li className={'mobildisabled'}><NavLink to={'/personnel/service'}>Service</NavLink></li>

                </ul>
            </div>
        );
            }
}
export default Personnel;
