import React, {useContext, useEffect, useState} from 'react';
import ReactQuill from "react-quill";
import 'react-quill/dist/quill.snow.css';
import axios from "axios";
import CardComponent from "../../props/CardComponent";
import userContext from "../../context/UserContext";

function AnnoncesInfos(props) {
    const [annonces, setAnnonces] = useState('');
    const [actus, setActus] = useState('');
    const [utils, setUtils] = useState('');
    const user= useContext(userContext);


    useEffect(async () => {
        await axios({
            method: 'GET',
            url: '/data/mgt/utils',
        }).then(r => setUtils(r.data.infos));
    }, [])

    return (<div className={'InfosAnnonces'}>
        <CardComponent title={'Annonces'}>
            <div className={'helper'}>
                <label>pas de liens ici </label>
                <button className={'btn'} disabled={!(user.grade.admin || user.post_annonces)}><img alt={''} src={'/assets/images/save.png'} onClick={async () => {
                    await axios({
                        method: 'POST',
                        url: '/data/mgt/annonce',
                        data: {
                            'text': annonces
                        }
                    }).then(r=>setAnnonces(''));
                }}/></button>
            </div>
            <div className={'writing'}>
                <ReactQuill value={annonces} onChange={(value)=>setAnnonces(value)}/>
            </div>
        </CardComponent>

        <CardComponent title={'Actualitées'}>
            <div className={'helper'}>
                <label>Les actus apparaissent que sur le site</label>
                <button className={'btn'} disabled={!(user.grade.admin || user.post_actualities)} onClick={async () => {
                    await axios({
                        method: 'POST',
                        url: '/data/mgt/actu',
                        data: {
                            'text': actus
                        }
                    }).then(r=>setActus(''));
                }}><img alt={''} src={'/assets/images/save.png'}/></button>
            </div>
            <div className={'writing'}>
                <ReactQuill value={actus} onChange={(value)=>setActus(value)}/>
            </div>
        </CardComponent>

        <CardComponent title={'Liens utiles'}>

            <div className={'helper'}>
                <label>	&nbsp;</label>
                <button className={'btn'} disabled={!(user.grade.admin || user.edit_infos_utils)} onClick={async () => {
                    await axios({
                        method: 'PUT',
                        url: '/data/mgt/utils',
                        data: {
                            'text': utils
                        }
                    }).then(r => setUtils(r.data.infos));
                }}><img alt={''} src={'/assets/images/save.png'}/></button>
            </div>
            <div className={'writing'}>
                <ReactQuill value={utils} onChange={(value)=>setUtils(value)}/>
            </div>
        </CardComponent>

    </div> )
}

export default AnnoncesInfos;
