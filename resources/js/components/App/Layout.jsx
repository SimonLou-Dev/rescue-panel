import React, {useEffect, useState} from 'react';
import axios from "axios";
import GetInfos from "../AuthComponent/GetInfos";
import {Link, Route} from "react-router-dom";
import Maintenance from "../Maintenance";
import {useNotifications} from "../context/NotificationProvider";
import UserContext from "../context/UserContext";
const mysrcf = csrf;
import {v4} from "uuid";
import Rapport from "./Patient/Rapports/Rapport";
import DossiersPatient from "./Patient/Dossiers/DossiersPatient";
import RapportReview from "./Patient/Dossiers/RapportReview";
import Psycology from "./Patient/Dossiers/Psycology";
import TestPoudre from "./Patient/TestPoudre/TestPoudre";
import FactureList from "./Patient/Factures/FactureList";
import FireBC from "./Patient/BlackCode/FireBC";


function Layout(props) {
    const [collapsed, setCollasping] = useState(true);
    const [user, setUser] = useState([]);
    const [service, setService] = useState('LSCoFD');
    const dispatch = useNotifications();

    useEffect(async ()=>{
        let userid = undefined
        await axios({
            method: 'GET',
            url: '/data/userInfos',
        }).then((response)=>{
            userid = response.data.user.id;
            setUser(response.data.user);
        })
        const timerID = setInterval(
            () => tick(),
            5*60*1000
        );

        Pusher.logToConsole = true;

        let pusher = new Pusher('fd78f74e8faecbd2405b', {
            cluster: 'eu',
            authEndpoint : '/broadcasting/auth',
            auth: { headers: { "X-CSRF-Token": mysrcf } }
        });

        let UserChannel = pusher.subscribe('private-User.'+env+'.'+ userid)
        UserChannel.bind('notify', (e)=>{
            addNotification(e)
        })

        let GlobalChannel = pusher.subscribe('presece-GlobalChannel')
        GlobalChannel.bind('DispatchUpdated', (e)=>{
            console.log(e)
        });
        GlobalChannel.bind('Notification', (data)=>{
            addNotification(data)
        });

        return () => {
            clearInterval(timerID);
        }

    }, [])

    const addNotification = (data) => {
        if(!data.type){
            data.type = 'warning';
        }

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

    const tick =async() => {
        let req =await axios({
            url: '/data/check/connexion',
            method: 'GET'
        }).then(response => {
            if(!response.data.session) {
                window.location.replace('/login')
            }
        }).catch( error => {
            if(error.response.status === 503){
                window.location.replace('/maintenance')
            }
        })

    }

    return (
        <div className={"layout"}>
            <header className={"layout-header"}>
                <div className={"header-menu"} onClick={()=>{setCollasping(!collapsed)}}>
                    <img src={'/assets/images/menu.png'} alt={""}/>
                    <h1>menu</h1>
                    <img src={'/assets/images/'+service+ '.png'} alt={""} className={'service-name'}/>
                </div>
                <div className={"header-logout"}>
                    <img src={'/assets/images/logout.png'} alt={""}/>
                </div>
            </header>
            {!collapsed &&
                <div className={"menu"}>
                    <section className={"menu-header"}>
                        <Link className={"menu-link-big"} to="/dashboard">tableau de bord</Link>
                        <Link className={"menu-link-big"} to="/account">mon compte</Link>
                        <Link className={"menu-link-big"} to={"/dispatch/"+service} >dispatch</Link>
                        <Link className={"menu-link-big"} to="/servicenav">changer de service</Link>
                        <h4 className={"menu-link-big"}>service : <label for="service-state">on</label></h4>
                    </section>
                    <section className={"menu-scrollable"}>
                        <div className={"menu-item-list"}>
                            <section className={"menu-item"}>
                                <h2><span>Patient</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/patients/rapport'} className={'menu-link'}>rapports</Link></li>
                                    <li className={'menu-puce'}><Link to={'/patients/dossiers'} className={'menu-link'}>dossiers</Link></li>
                                    <li className={'menu-puce'}><Link to={'/patients/poudre'} className={'menu-link'}>tests de poudre</Link></li>
                                    <li className={'menu-puce'}><Link to={'/blackcodes/all'} className={'menu-link'}>BC - Incendies</Link></li>
                                </ul>
                            </section>
                            <section className={"menu-item"}>
                                <h2><span>Factures</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/factures'} className={'menu-link'}>factures</Link></li>
                                </ul>
                            </section>
                            <section className={"menu-item"}>
                                <h2><span>Formations</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/formation/questionnaires'} className={'menu-link'}>questionnaires</Link></li>
                                    <li className={'menu-puce'}><Link to={'/formation/admin'} className={'menu-link'}>création</Link></li>
                                </ul>
                            </section>
                            <section className={"menu-item"}>
                                <h2><span>Logistique</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/logistique/stock'} className={'menu-link'}>gestion des stocks</Link></li>
                                </ul>
                            </section>
                            <section className={"menu-item"}>
                                <h2><span>Personnel</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/personnel/grade'} className={'menu-link'}>grade</Link></li>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/personnel/horaire'} className={'menu-link'}>rapport horaire</Link></li>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/personnel/personnel'} className={'menu-link'}>liste du personnel</Link></li>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/personnel/demandes'} className={'menu-link'}>demandes</Link></li>
                                </ul>
                            </section>
                            <section className={"menu-item"}>
                                <h2><span>Gestion MDT</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/mdt/discord'} className={'menu-link'}>discord</Link></li>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/mdt/logs'} className={'menu-link'}>logs</Link></li>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/mdt/content'} className={'menu-link'}>gestion de contenues</Link></li>
                                    <li className={'menu-puce'}><Link to={'/'+service+ '/mdt/infos'} className={'menu-link'}>info / annonces</Link></li>
                                </ul>
                            </section>
                        </div>
                    </section>
                    <section className={"menu-footer"}>
                        <h3>Design & développement
                            Simon Lou - Copyright ©</h3>
                    </section>
                </div>
            }
            <div className={'app-page-container'}>
                <UserContext.Provider value={user}>
                    <Route path={'/patients/rapport'} component={Rapport}/>
                    <Route path={'/patients/dossiers'} component={DossiersPatient}/>
                    <Route path={'/patients/:patientId/view'} component={RapportReview}/>
                    <Route path={'/patients/:patientId/psy'} component={Psycology}/>
                    <Route path={'/patients/poudre'} component={TestPoudre}/>
                    <Route path={'/:service/factures/'} component={FactureList}/>

                    <Route path={'/blackcodes/:bcID'} component={FireBC}/>

                </UserContext.Provider>
            </div>
        </div>
    )
}

export default Layout;
