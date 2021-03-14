import React from "react";
import {NavLink} from "react-router-dom";


class Gestion extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            admin: false,
        }
    }

    async componentWillMount() {
        var req = await axios({
            url: '/data/userisadmin',
            method: 'get',
        });
        this.setState({admin: req.data.IsAdmin});

    }
    render() {
        if(this.state.admin){
            return(<div className="Menu-Item" id="Administration">
                    <h2 className="Menu_title"><span>Gestion</span></h2>
                    <ul className="Menu-list">
                        <li className={'mobildisabled'}><NavLink to={'/gestion/rapport'}>Rapport horaire</NavLink></li>
                        <li><NavLink to={'/gestion/content'}>Gestion contenu</NavLink></li>
                        <li><NavLink to={'/gestion/personnel'}>Personnel</NavLink></li>
                        <li><NavLink to={'/gestion/log'}>Logs</NavLink></li>
                        <li><NavLink to={'/gestion/formation'}>Formations</NavLink></li>
                    </ul>
                </div>
            );
        }else{
            return null;
        }
    }
}
export default Gestion;
