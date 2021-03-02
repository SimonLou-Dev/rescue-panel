import React from "react";
import {NavLink} from "react-router-dom";

class Personnel extends React.Component{
    render() {
        return (
            <div className="Menu-Item" id="Service">
                <h2 className="Menu_title"><span>Personnel</span></h2>
                <ul className="Menu-list">
                    <li className={'mobildisabled'}><NavLink to={'/personnel/service'}>Service</NavLink></li>
                    <li><NavLink to={'/personnel/factures'}>Factures</NavLink></li>
                    <li><NavLink to={'/personnel/remboursement'}>Remboursement</NavLink></li>
                    <li><NavLink to={'/personnel/informations'}>Informations</NavLink></li>
                    <li><NavLink to={'/personnel/moncompte'}>Mon Compte</NavLink></li>
                    <li><NavLink to={'/personnel/livret'}>Mes formations</NavLink></li>
                    <li><NavLink to={'/personnel/vols'}>Carnet de vol</NavLink></li>
                </ul>
            </div>
        );
            }
}
export default Personnel;
