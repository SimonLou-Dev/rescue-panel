import React from "react";
import {NavLink} from "react-router-dom";


class Gestion extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            total: false,
            forma: false,
            logs: false,
            content: false,
            personnel: false,
            time:false,
        }
    }

    componentDidMount() {
        if(this.props.perm['rapport_horaire']){
            this.setState({time:true, total:true});
        }
        if(this.props.perm['perso_list']){
            this.setState({personnel:true, total:true});
        }
        if(this.props.perm['post_annonces']){
            this.setState({content:true, total:true});
        }
        if(this.props.perm['log_acces']){
            this.setState({logs:true, total:true});
        }
        if(this.props.perm['validate_forma']){
            this.setState({forma:true, total:true});
        }
        if(this.props.perm['content_mgt']){
            this.setState({content:true, total:true});
        }
    }

    render() {
            return(
                this.state.total &&
                <div className="Menu-Item" id="Administration">
                    <h2 className="Menu_title"><span>Gestion</span></h2>
                    <ul className="Menu-list">
                        {this.state.time &&
                            <li className={'mobildisabled'}><NavLink to={'/gestion/rapport'}>Rapport horaire</NavLink></li>
                        }
                        {this.state.content &&
                            <li><NavLink to={'/gestion/content'}>Gestion contenu</NavLink></li>
                        }
                        {this.state.personnel &&
                            <li><NavLink to={'/gestion/personnel'}>Personnel</NavLink></li>
                        }
                        {this.state.logs &&
                            <li><NavLink to={'/gestion/log'}>Logs</NavLink></li>
                        }
                        {this.state.forma &&
                            <li><NavLink to={'/gestion/formation'}>Formations</NavLink></li>
                        }
                    </ul>
                </div>
            );
    }
}
export default Gestion;
