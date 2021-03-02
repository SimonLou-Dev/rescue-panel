import React from 'react';
import axios from "axios";
import Service from "./props/Menu/Service";
import Personnel from "./props/Menu/Personnel";
import Patient from "./props/Menu/Patient";
import Gestion from "./props/Menu/Gestion";
import {NavLink, Route, Switch} from "react-router-dom";
import Main from "./Main";
import Rapport from "./Patient/rapport";
import Urgence from "./Patient/Urgence";
import RecherchePatient from "./Patient/RecherchePatient";
import Services from "./Personnel/Services";
import Impayes from "./Personnel/Impayes";
import RapportHoraire from "./Gestion/RapportHoraire";
import ContentManagement from "./Gestion/ContentManagement";
import PersonnelList from './Gestion/PersonnelList';
import Logs from "./Gestion/Logs";
import {useLayoutEffect, useState} from "react/cjs/react.production.min";

const service_state = false;
export const rootUrl = document.querySelector('body').getAttribute('data-root-url');
var admin;



class Layout extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            openmenu : false,
            minview: false
        }
        this.updateWindowDimensions = this.updateWindowDimensions.bind(this)

    }

    componentDidMount() {
        this.updateWindowDimensions();
        window.addEventListener("resize", this.updateWindowDimensions);
        this.timerID = setInterval(
            () => this.tick(),
            5*60*1000
        );
    }

    componentWillUnmount() {
        window.removeEventListener("resize", this.updateWindowDimensions);
        clearInterval(this.timerID);
    }

    async tick() {
        var req = await axios({
            url: '/data/checkco',
            method: 'GET'
        })
        if (!req.data.session) {
            window.location.replace('/login')
        }
    }

    updateWindowDimensions() {
        this.setState({ width: window.innerWidth, height: window.innerHeight });
        if(window.innerWidth < 1100 ){
            this.setState({minview:true})
        }else{
            this.setState({minview:false})
        }

    }


    render() {
        return(
            <div id="layout">
                <div id="Menu" className={this.state.minview?(this.state.openmenu? 'open collapsed' : 'close collapsed') : null}>
                    <div className={'closed-menu'}>
                        <button onClick={()=>{
                            this.setState({openmenu : true});
                        }}>Menu</button>
                    </div>
                    <div className={'menu-content'}>
                        <div id={'logout'}>
                            <a href={'/logout'}><img src={'/assets/images/logout.svg'}/></a>
                        </div>
                        <div id={'Close'}>
                            <button onClick={()=>{
                                this.setState({openmenu : false});
                            }}>fermer</button>
                        </div>
                        <div id="Logo">
                            <NavLink to={'/'}><img src={'/assets/images/BCFD.svg'}/></NavLink>
                        </div>
                        <div className="Menusepartor"/>
                        <Service status={service_state}/>
                        <div className="Menusepartor"/>
                        <div className="navigation">
                            <Patient service={service_state}/>
                            <Personnel/>
                            <Gestion service={service_state}/>
                        </div>
                        <div className="Menusepartor"/>
                        <div className="Copyright">
                            <p>Design & développement Simon Lou - Copyright &copy;</p>
                        </div>
                    </div>

                </div>
                <div id="content">
                        <Route exact path='/' component={Main}/>
                        <Route path='/patient/rapport' component={Rapport}/>
                        <Route path={'/patient/urgence'} component={Urgence}/>
                        <Route path={'/patient/dossiers'} component={RecherchePatient}/>
                        <Route path={'/personnel/service'} component={Services}/>
                        <Route path={'/personnel/factures'} component={Impayes}/>;
                        <Route path={'/gestion/rapport'} component={RapportHoraire}/>
                        <Route path={'/gestion/content'} component={ContentManagement}/>
                        <Route path={'/gestion/personnel'} component={PersonnelList}/>
                        <Route path={'/gestion/log'} component={Logs}/>
                </div>
            </div>
        );
    }
}
export default Layout;

