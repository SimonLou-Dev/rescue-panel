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

        if(this.props.perm !== prevProps.perm && this.props.perm !== null){
            if(!this.props.service){
                if(this.props.perm['HS_facture']){
                    this.setState({facture:true})
                }else{
                    this.setState({facture:false})
                }
            }
        }

        if(this.props.user !== prevProps.user && this.props.user !== null){
            if(this.props.user.pilote){
                this.setState({vols:true})
            }else{
                this.setState({vols:false})
            }
        }
    }

    render() {
        return (
            <div className="Menu-Item">
                <h2 className="Menu_title"><span>Personnel</span></h2>
                <ul className="Menu-list">
                    <li><NavLink to={'/personnel/service'}>Service</NavLink></li>
                    {this.state.facture &&
                        <li><NavLink to={'/personnel/factures'}>Factures</NavLink></li>
                    }
                    <li><NavLink to={'/personnel/remboursement'}>Remboursement</NavLink></li>
                    <li><NavLink to={'/personnel/moncompte'}>Mon Compte</NavLink></li>
                    <li><NavLink to={'/personnel/livret'}>Mes formations</NavLink></li>

                    {this.state.vols &&
                        <li><NavLink to={'/personnel/vols'}>Carnet de vol</NavLink></li>
                    }
                </ul>
            </div>
        );
            }
}
export default Personnel;
