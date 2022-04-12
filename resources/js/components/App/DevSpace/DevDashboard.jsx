import React, {useContext, useEffect, useState} from 'react';
import axios from 'axios';
import CardComponent from "../../props/CardComponent";
import {Link, useHistory} from "react-router-dom";
import UserContext from "../../context/UserContext";

function DevDashboard(props) {
    const [numberOfUser, setNumberOfUser] = useState(0);
    const [medicService, setMedicService] = useState([]);
    const [fireService, setFireService] = useState([])
    const [files, setFiles] = useState([]);
    const history = useHistory();
    const user= useContext(UserContext)

    useEffect(async () => {
        if(!user.dev || user.medic_grade_id !== 7 || user.fire_grade_id !== 7) history.push('/dashboad')

        let GlobalChannel = window.GlobalChannel;

        GlobalChannel.bind('pusher:subscription_succeeded', (members) => {
            setNumberOfUser(GlobalChannel.members.count);
        })
        GlobalChannel.bind('pusher:member_added', (members) => {
            setNumberOfUser(GlobalChannel.members.count);
        })
        GlobalChannel.bind('pusher:member_removed', (members) => {
            setNumberOfUser(GlobalChannel.members.count);
        })

        //Service updater
        GlobalChannel.bind('ServiceUpdated', (e) => {
            listService()
        })

        await axios({
            method: 'GET',
            url: '/data/dev/files/all'
        }).then(r => {
            setFiles(r.data.files)
        })
        listService()

        return () => {
            GlobalChannel.unbind('pusher:member_removed');
            GlobalChannel.unbind('pusher:member_added');
            GlobalChannel.unbind('ServiceUpdated');
            GlobalChannel.unbind('pusher:subscription_succeeded');
        }

    }, [])


    const listService = async () => {
        await axios({
            method: 'GET',
            url: '/data/dev/services'
        }).then(r => {
            setFireService(r.data.fire)
            setMedicService(r.data.medic)
        })
    }

    return (<div className={'DevDashboard'}>
        <section className={'service'}>
            <div className={'onLineCounter'}>
                <label>En ligne {numberOfUser}</label>
            </div>
            <CardComponent title={'Service au SAMS (' + medicService.length +  ')'} className={'serviceList'}>
                <div className={'personnel-list'}>
                    {medicService && medicService.map((med) =>
                        <p className={'personnel-tag'} key={med.id}>
                            {med.name}
                        </p>
                    )}
                </div>
            </CardComponent>
            <CardComponent title={'Service au LSCoFD (' + fireService.length +')'} className={'serviceList'}>
                <div className={'personnel-list'}>
                    {fireService && fireService.map((fire) =>
                        <p className={'personnel-tag'} key={fire.id}>
                            {fire.name}
                        </p>
                    )}
                </div>
            </CardComponent>
        </section>

        <section className={'logs'}>
            <div className={'LogsList'}>
                <table>
                    <thead>
                        <tr>
                            <th>nom</th>
                            <th>taille</th>
                            <th>voir</th>
                        </tr>
                    </thead>
                    <tbody>
                    {files && files.map((f) =>
                        <tr key={'f'+ f.name}>
                            <td>{f.name}</td>
                            <td>{f.size}</td>
                            <td><a target={"_blank"} href={'/data/dev/files/'+f.name}><img alt={''} src={'/assets/images/documents.png'}/></a> </td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </section>

    </div>)
}

export default DevDashboard;
