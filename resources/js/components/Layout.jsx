import React, {useContext, useState, useEffect} from 'react';
import axios from "axios";
import Service from "./props/Menu/Service";
import Personnel from "./props/Menu/Personnel";
import Patient from "./props/Menu/Patient";
import Gestion from "./props/Menu/Gestion";
import {NavLink, Route} from "react-router-dom";
import Main from "./Main";
import Rapport from "./Patient/rapport";
import RecherchePatient from "./Patient/RecherchePatient";
import Services from "./Personnel/Services";
import Factures from "./Personnel/Factures";
import RapportHoraire from "./Gestion/RapportHoraire";
import ContentManagement from "./Gestion/ContentManagement";
import PersonnelList from './Gestion/PersonnelList';
import Logs from "./Gestion/Logs";
import Permissions from "./Gestion/Permissions";
import BCController from "./Patient/BCController";
import AFormaController from "./Gestion/AFormaController";
import InfoGestion from "./Gestion/InfoGestion";
import Informations from "./Personnel/Informations";
import MonCompte from "./Personnel/MonCompte";
import FormationsController from "./Personnel/FormationsController";
import CarnetVol from "./Personnel/CarnetVol";
import Remboursement from "./Personnel/Remboursement";
import BugRepport from "./BugRepport";
import PermsContext from "./context/PermsContext";
import 'animate.css'
import dateFormat from "dateformat";
import {v4} from "uuid";
import NotificationContext from "./context/NotificationContext";
import {useNotifications} from "./context/NotificationProvider";

export const rootUrl = document.querySelector('body').getAttribute('data-root-url');

class Time extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            date: '',
        }
    }

    async componentDidMount() {
        this.tick();
        this.timerID = setInterval(
            () => this.tick(),
            1000
        );

    }

    tick(){
        var d = dateFormat(new Date(), 'HH:MM:ss');
        this.setState({date: d})
    }
    componentWillUnmount() {
        clearInterval(this.timerID);
    }

    render() {
        return (<div className="time">
            <h3>{this.state.date}</h3>
        </div>);
    }
}

class ErrorBoundary extends React.Component{

    componentDidCatch(error, errorInfo) {
        console.log(error, errorInfo)
    }

    render() {
        return this.props.children
    }
}

class Layout extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            openmenu : false,
            minview: false,
            bug:false,
            perms: [],
            serviceStatus: false,
            user: [],
            style:null,
            context: {
                perms: {},
            },
            hasError:false,
        }
        this.updateWindowDimensions = this.updateWindowDimensions.bind(this)

    }

    async componentDidMount() {
        let req = await axios({
            url: '/data/getperm',
            method: 'get',

        });
        this.setState({
            perms: req.data.perm,
            context:req.data.perm,
            user: req.data.user,
        });
        this.updateWindowDimensions();


        let data = {
            type: 1,
            text: 'test'
        }
        console.log(useNotifications({}))
        this.addNotification(data);



    }

     addNotification = (data) => {
        let payload = {};
        switch (data.type){
            case 1:
                payload= {
                    id:v4(),
                    type: 'success',
                    message: data.text
                }
                break
            case 2:
                payload= {
                    id:v4(),
                    type: 'info',
                    message: data.text
                }
                break;
            case 3:
                payload= {
                    id:v4(),
                    type: 'warning',
                    message: data.text
                }
                break;
            case 4:
                payload= {
                    id:v4(),
                    type: 'danger',
                    message: data.text
                }
                break;
            default: break;
        }
        this.context.dispatch({
            type: 'ADD_NOTIFICATION',
            payload,
        })
    }

    componentWillUnmount() {
        window.removeEventListener("resize", this.updateWindowDimensions);
        clearInterval(this.timerID);
    }

    async tick() {
        let req = await axios({
            url: '/data/check/connexion',
            method: 'GET'
        })
        if (!req.data.session) {
            window.location.replace('/login')
        }
    }

    updateWindowDimensions = () => {
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
                <style dangerouslySetInnerHTML={{__html:'#layout::before{'+this.state.style+'}'}}/>
                <div id="Menu" className={this.state.minview?(this.state.openmenu? 'open collapsed' : 'close collapsed') : null}>
                    <div className={'closed-menu'}>
                        <button onClick={()=>{
                            this.setState({openmenu : true});
                        }}>Menu</button>
                    </div>
                    <div className={'menu-content'} >
                        <div id={'logout'}>
                            <a href={'/logout'}><img src={'/assets/images/logout.svg'} alt={''}/></a>
                        </div>
                        <Time/>
                        <div id={'Close'}>
                            <button onClick={()=>{
                                this.setState({openmenu : false});
                            }}>fermer</button>
                        </div>
                        <div id="Logo">
                            <NavLink to={'/'}><img src={'/assets/images/BCFD.svg'} alt={''}/></NavLink>
                        </div>
                        <div className="Menusepartor"/>
                        <Service serviceUpade={async (state) => {
                            this.setState({serviceStatus: state})
                        }}/>
                        <div className="Menusepartor"/>
                        <div className="navigation">
                            <Patient service={this.state.serviceStatus} perm={this.state.perms}/>
                            <Personnel service={this.state.serviceStatus} perm={this.state.perms} user={this.state.user}/>
                            <Gestion perm={this.state.perms}/>
                        </div>
                        <div className="Menusepartor"/>
                        <div className="bugreportter">
                            <button className={'btn'} onClick={()=>{this.setState({bug:true})}}>Signaler un bug</button>
                        </div>
                        <div className="Menusepartor"/>
                        <div className="Copyright">
                            <p>Design & développement Simon Lou - Copyright &copy;</p>
                        </div>
                    </div>
                </div>
                <div id="content" style={{filter: this.state.bug ? 'blur(5px)' : 'none'}} >

                            <PermsContext.Provider value={this.state.context}>
                                <Route exact path='/' component={Main}/>
                                <Route path={'/bugrepport'} component={BugRepport}/>

                                <Route path='/patient/rapport' component={Rapport}/>
                                <Route path={'/patient/blackcode'} component={BCController}/>
                                <Route path={'/patient/dossiers'} component={RecherchePatient}/>

                                <Route path={'/personnel/service'} component={Services}/>
                                <Route path={'/personnel/factures'} component={Factures}/>
                                <Route path={'/personnel/informations'} component={Informations}/>
                                <Route path={'/personnel/moncompte'} component={MonCompte}/>
                                <Route path={'/personnel/livret'} component={FormationsController}/>
                                <Route path={'/personnel/vols'} component={CarnetVol}/>
                                <Route path={'/personnel/remboursement'} component={Remboursement}/>

                                <Route path={'/gestion/rapport'} component={RapportHoraire}/>
                                <Route path={'/gestion/content'} component={ContentManagement}/>
                                <Route path={'/gestion/personnel'} component={PersonnelList}/>
                                <Route path={'/gestion/log'} component={Logs}/>
                                <Route path={'/gestion/formation'} component={AFormaController}/>
                                <Route path={'/gestion/informations'} component={InfoGestion}/>
                                <Route path={'/gestion/perm'} component={Permissions}/>
                            </PermsContext.Provider>
                </div>
                {this.state.bug &&
                    <BugRepport close={()=>this.setState({bug:false})}/>
                }
            </div>
        );
    }
}

export function NewLayout(){
    const a = 0;
    const [dimentions, setDimentions] = useState({width: 0, height:0});
    const [minView, setminView] = useState(false);
    const [bugPopup, openPopup] = useState(false);
    const [menuOpened, openMenu] = useState(false);
    const [service, setService] = useState(false);
    const [user, setUser] = useState({});
    const [style, setStyle] = useState(null);
    const [perm, setPerm] = useState({});
    const [chanInit, InitializeChan] = useState(false);
    const dispatch = useNotifications();

    //user infos and windows data
    useEffect(async ()=>{
        let req = await axios({
            url: '/data/getperm',
            method: 'get',
        });
        setPerm(req.data.perm);
        setUser(req.data.user);
        setStyle('background-image:' + (req.data.user.bg_img === null ? 'none' : 'url(/storage/user_background/' + req.data.user.id + '/'+ req.data.user.bg_img+');' ))
        updateWindowDimensions()
        window.addEventListener("resize", updateWindowDimensions);
        const timerID = setInterval(
            () => tick(),
            5*60*1000
        );

        Pusher.logToConsole = true;

        let pusher = new Pusher('fd78f74e8faecbd2405b', {
            cluster: 'eu'
        });
        let userChan = pusher.subscribe('UserChannel_'+req.data.user.id);
        userChan.bind('notify', (data)=>{addNotification(data)});

        let BroadCastChan = pusher.subscribe('Broadcater');
        BroadCastChan.bind('notify', function(data) {
            store.addNotification({
                message: data.text,
                type: 'warning',                         // 'default', 'success', 'info', 'warning'
                container: 'top-right',                // where to position the notifications
                animationIn: ["animate__animated", "animate__fadeInRight"],     // animate.css classes that's applied
                animationOut: ["animate__animated", "animate__fadeOutDown"],   // animate.css classes that's applied
                dismiss: {
                    duration: 3000,
                    onScreen: true
                }
            })
        })

        return () => {
            window.removeEventListener("resize", updateWindowDimensions);
            clearInterval(timerID);
        }

    }, [])



    const tick = async () => {
        let req = await axios({
            url: '/data/check/connexion',
            method: 'GET'
        })
        if (!req.data.session) {
            window.location.replace('/login')
        }
    }

    const updateWindowDimensions = () => {
        setDimentions({width: window.innerWidth, height: window.innerHeight})
        if(window.innerWidth < 1100){
            setminView(true)
        }else{
            setminView(false)
        }
    }

    const addNotification = (data) => {
        let payload = {};
        switch (data.type){
            case 1:
                payload= {
                    id:v4(),
                    type: 'success',
                    message: data.text
                }
                break
            case 2:
                payload= {
                    id:v4(),
                    type: 'info',
                    message: data.text
                }
                break;
            case 3:
                payload= {
                    id:v4(),
                    type: 'warning',
                    message: data.text
                }
                break;
            case 4:
                payload= {
                    id:v4(),
                    type: 'danger',
                    message: data.text
                }
                break;
            default: break;
        }
        dispatch({
            type: 'ADD_NOTIFICATION',
            payload: {
                id: payload.id,
                type: payload.type,
                message: payload.message
            }
        });
    }

    return(
        <div id="layout">
            <style dangerouslySetInnerHTML={{__html:'#layout::before{'+style+'}'}}/>
            <div id="Menu" className={minView?(menuOpened? 'open collapsed' : 'close collapsed') : null}>
                <div className={'closed-menu'}>
                    <button onClick={()=>{
                        openMenu(true)
                    }}>Menu</button>
                </div>
                <div className={'menu-content'} >
                    <div id={'logout'}>
                        <a href={'/logout'}><img src={'/assets/images/logout.svg'} alt={''}/></a>
                    </div>
                    <Time/>
                    <div id={'Close'}>
                        <button onClick={()=>{
                            openMenu(false)
                        }}>fermer</button>
                    </div>
                    <div id="Logo">
                        <NavLink to={'/'}><img src={'/assets/images/BCFD.svg'} alt={''}/></NavLink>
                    </div>
                    <div className="Menusepartor"/>
                    <Service serviceUpade={async (state) => {
                        setService(state)
                    }}/>
                    <div className="Menusepartor"/>
                    <div className="navigation">
                        <Patient service={service} perm={perm}/>
                        <Personnel service={service} perm={perm} user={user}/>
                        <Gestion perm={perm}/>
                    </div>
                    <div className="Menusepartor"/>
                    <div className="bugreportter">
                        <button className={'btn'} onClick={()=>{openPopup(true)}}>Signaler un bug</button>
                    </div>
                    <div className="Menusepartor"/>
                    <div className="Copyright">
                        <p>Design & développement Simon Lou - Copyright &copy;</p>
                    </div>
                </div>
            </div>
            <div id="content" style={{filter: bugPopup ? 'blur(5px)' : 'none'}} >

                <PermsContext.Provider value={{perm: perm}}>
                    <Route exact path='/' component={Main}/>
                    <Route path={'/bugrepport'} component={BugRepport}/>

                    <Route path='/patient/rapport' component={Rapport}/>
                    <Route path={'/patient/blackcode'} component={BCController}/>
                    <Route path={'/patient/dossiers'} component={RecherchePatient}/>

                    <Route path={'/personnel/service'} component={Services}/>
                    <Route path={'/personnel/factures'} component={Factures}/>
                    <Route path={'/personnel/informations'} component={Informations}/>
                    <Route path={'/personnel/moncompte'} component={MonCompte}/>
                    <Route path={'/personnel/livret'} component={FormationsController}/>
                    <Route path={'/personnel/vols'} component={CarnetVol}/>
                    <Route path={'/personnel/remboursement'} component={Remboursement}/>

                    <Route path={'/gestion/rapport'} component={RapportHoraire}/>
                    <Route path={'/gestion/content'} component={ContentManagement}/>
                    <Route path={'/gestion/personnel'} component={PersonnelList}/>
                    <Route path={'/gestion/log'} component={Logs}/>
                    <Route path={'/gestion/formation'} component={AFormaController}/>
                    <Route path={'/gestion/informations'} component={InfoGestion}/>
                    <Route path={'/gestion/perm'} component={Permissions}/>
                </PermsContext.Provider>
            </div>
            {bugPopup &&
            <BugRepport close={()=>openPopup(false)}/>
            }
        </div>
    );
}

export default Layout;

