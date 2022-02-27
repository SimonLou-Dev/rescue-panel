import React, {useContext, useEffect, useState} from 'react';
import CardComponent from "../../props/CardComponent";
import axios from "axios";
import UserContext from "../../context/UserContext";

function Dashboard(props) {
    const [annonces, setAnnonces] = useState([])
    const [actus, setActus] = useState([])
    const [infos, setInfos] = useState([])
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
    },[])


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
        </section>


    </div> )
}

export default Dashboard;
