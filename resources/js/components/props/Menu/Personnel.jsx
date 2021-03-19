import React from "react";
import {NavLink} from "react-router-dom";

class Personnel extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            facture:false,
            vols:false,
        }
    }

    componentDidMount() {
        if(this.props.service && this.props.user.pilote){
            this.setState({vol:true})
        }
        if(this.props.perm['vol']) {
            this.setState({vol: true})
        }
        if(this.props.service){
            this.setState({facture:true})
        }
        if(this.props.perm['HS_factures']){
            this.setState({BC:true})
        }
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if(this.props.service !== prevProps.service){
            if(this.props.service === true){
                if(this.props.user.pilote){
                    this.setState({vol:true})
                }
                this.setState({facture:true})

            }else{
                this.setState({facture:false})
                this.setState({vol:false})
            }
        }
    }

    render() {
        return (
            <div className="Menu-Item" id="Service">
                <h2 className="Menu_title"><span>Personnel</span></h2>
                <ul className="Menu-list">
                    <li className={'mobildisabled'}><NavLink to={'/personnel/service'}>Service</NavLink></li>
                    {this.state.facture &&
                        <li><NavLink to={'/personnel/factures'}>Factures</NavLink></li>
                    }
                    <li><NavLink to={'/personnel/remboursement'}>Remboursement</NavLink></li>
                    <li><NavLink to={'/personnel/moncompte'}>Mon Compte</NavLink></li>
                    <li><NavLink to={'/personnel/livret'}>Mes formations</NavLink></li>
                    {this.state.vol &&
                        <li><NavLink to={'/personnel/vols'}>Carnet de vol</NavLink></li>
                    }
                </ul>
            </div>
        );
            }
}
export default Personnel;
