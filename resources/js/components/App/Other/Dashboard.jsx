import React, {useContext, useEffect, useState} from 'react';
import CardComponent from "../../props/CardComponent";
import axios from "axios";
import UserContext from "../../context/UserContext";
import {setUser} from "@sentry/react";

function Dashboard(props) {
    const [annonces, setAnnonces] = useState([])
    const [actus, setActus] = useState([])
    const [infos, setInfos] = useState([])
    const [services, setServices] = useState([])
    const user = useContext(UserContext);


    useEffect(async () => {
        await axios({
            method: 'GET',
            url: '/data/dashboard'
        }).then(r => {
            setAnnonces(r.data.annonces)
            setActus(r.data.actus)
            setInfos(r.data.infos)
        })
        getUsersInServiceInUnit();

        let GlobalChannel = window.GlobalChannel;
        GlobalChannel.bind('ServiceUpdated', (e)=>{
            if(e.service === user.service){
                getUsersInServiceInUnit(e.users);
            }
        })
        return () => {
            GlobalChannel.unbind('ServiceUpdated');
        }

    },[])

    const getUsersInServiceInUnit = async (users = undefined) => {
        if(users === undefined){
            await axios({
                method: 'GET',
                url: '/data/service/users'
            }).then(r => {
                users = r.data.users
            })

        }
        setServices(users)
    }


    return (<div className={'dashboard'}>
        <CardComponent title={'Annonces'} className={'annonces'}>
            {annonces && annonces.map((a)=>
                <div className={'rect-item'} key={a.id}>
                    <h4>{a.created_at}</h4>
                    <div className={'infos_content'} dangerouslySetInnerHTML={{__html:a.content}} />
                </div>
            )}
        </CardComponent>
        <section className={'middle'}>
            {user.service === 'SAMS' &&
                <div className={'infos'}>
                    <img src={'/assets/images/SAMS.png'} alt={''}/>
                    <h1>SAN ANDREAS MEDICAL SERVICE</h1>
                </div>
            }
            {user.service === 'LSCoFD' &&
                <div className={'infos'}>
                    <img src={'/assets/images/LSCoFD.png'} alt={''}/>
                    <h1>LOS SANTOS COUNTY FIRE DEPARTMENT</h1>
                </div>
            }

            <CardComponent title={'actualités'} className={'actus'}>
                    {actus && actus.map((a)=>
                        <div className={'rect-item'} key={a.id}>
                            <h4>{a.created_at}</h4>
                            <div className={'infos_content'} dangerouslySetInnerHTML={{__html:a.content}} />
                        </div>
                    )}
            </CardComponent>
        </section>
        <section className={'left'}>
            <div className={'me'}>
                <h4>Bienvenue {user.name} {(user.matricule ? ' - ' +user.matricule :  '')}</h4>
            </div>
            <CardComponent title={'liens utiles'} className={'utils'}>
                <div className={'infos'} dangerouslySetInnerHTML={{__html:(infos === null ? '' : infos.value)}}/>
            </CardComponent>
            <CardComponent title={'Personnel en service'} className={'service'}>
                <div className={'list-user'}>
                    {services && services.map((service)=>
                        <div className={'user-tag'} key={service.id}>
                            <p>{service.name}</p>
                        </div>
                    )}

                </div>
            </CardComponent>
        </section>


    </div> )
}

export default Dashboard;
