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
                </ul>
            </div>
        );
            }
}
export default Personnel;
