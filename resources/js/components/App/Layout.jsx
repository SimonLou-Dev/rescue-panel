import React, {useEffect, useState} from 'react';
import axios from "axios";
import Echo from 'laravel-echo';
import GetInfos from "../AuthComponent/GetInfos";
import {Link, Route} from "react-router-dom";
import Maintenance from "../Maintenance";
const mysrcf = csrf;


function Layout(props) {
    const [collapsed, setCollasping] = useState(false); // verif la syntax

    useEffect(async ()=>{
        let userId = 1;

       Pusher.logToConsole = true;
        let pusher = new Pusher('fd78f74e8faecbd2405b', {
            cluster: 'eu',
            authEndpoint : '/broadcasting/auth',
            auth: { headers: { "X-CSRF-Token": mysrcf } }
        });
        let UserChannel = pusher.subscribe('private-User.'+env+'.'+ userId);
        UserChannel.bind('notify', (data)=>{
            console.log(data);
        })

        let GlobalChannel = pusher.subscribe('presece-GlobalChannel');
        GlobalChannel.bind('DispatchUpdated', (e)=>{
            console.log(e)
        });
        GlobalChannel.bind('Notification', (e)=>{
            console.log(e)
        });

    }, [])

    return (
        <div className={"layout"}>
            <header className={"layout-header"}>
                <div className={"header-menu"} onClick={()=>{setCollasping(!collapsed)}}>
                    <img src={'/assets/images/menu.png'} alt={""}/>
                    <h1>menu</h1>
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
                        <Link className={"menu-link-big"} to="/dispatch">dispatch</Link>
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
                                    <li className={'menu-puce'}><Link to={'/factures'} className={'menu-link'}>factures</Link></li>
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
                                    <li className={'menu-puce'}><Link to={'/logistique/stock'} className={'menu-link'}>gestion des stocks</Link></li>
                                </ul>
                            </section>
                            <section className={"menu-item"}>
                                <h2><span>Personnel</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/personnel/grade'} className={'menu-link'}>grade</Link></li>
                                    <li className={'menu-puce'}><Link to={'/personnel/horaire'} className={'menu-link'}>rapport horaire</Link></li>
                                    <li className={'menu-puce'}><Link to={'/personnel/personnel'} className={'menu-link'}>liste du personnel</Link></li>
                                    <li className={'menu-puce'}><Link to={'/personnel/demandes'} className={'menu-link'}>demandes</Link></li>
                                </ul>
                            </section>
                            <section className={"menu-item"}>
                                <h2><span>Gestion MDT</span></h2>
                                <ul className={"menu-nav-list"}>
                                    <li className={'menu-puce'}><Link to={'/mdt/discord'} className={'menu-link'}>discord</Link></li>
                                    <li className={'menu-puce'}><Link to={'/mdt/logs'} className={'menu-link'}>logs</Link></li>
                                    <li className={'menu-puce'}><Link to={'/mdt/content'} className={'menu-link'}>gestion de contenues</Link></li>
                                    <li className={'menu-puce'}><Link to={'/mdt/infos'} className={'menu-link'}>info / annonces</Link></li>
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
        </div>
    )
}

export default Layout;
