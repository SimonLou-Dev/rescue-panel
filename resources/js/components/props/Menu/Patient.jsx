import React from "react";
import {NavLink} from "react-router-dom";

class Patient extends React.Component{
    render() {
        return(
            <div className="Menu-Item" id="Patient">
                <h2 className="Menu_title"><span>Patient</span></h2>
                <ul className="Menu-list">
                    <li><NavLink to={'/patient/rapport'}>Rapport  patient</NavLink></li>
                    <li><NavLink to={'/patient/blackcode'}>Black code</NavLink></li>
                    <li><NavLink to={'/patient/dossiers'}>Dossiers</NavLink></li>
                </ul>
            </div>
        );
    }
}
export default Patient;
