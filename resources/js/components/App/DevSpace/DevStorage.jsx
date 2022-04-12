import React, {useContext, useEffect, useState} from 'react';
import axios from 'axios';
import {useHistory} from "react-router-dom";
import UserContext from "../../context/UserContext";

function DevStorage(props) {
    const history = useHistory();
    const user= useContext(UserContext)
    const [filesList, setFilesList] = useState([]);

    useEffect(async () => {
        if (!user.dev || user.medic_grade_id !== 7 || user.fire_grade_id !== 7) history.push('/dashboad')
        getData()
    }, [])

    const deleteFile = async (name) => {
        await axios({
            method: 'DELETE',
            url: '/data/dev/storage/' + name
        }).then(() => {
            getData()
        })
    }

    const getData = async () => {
        await axios({
            method: 'GET',
            url: '/data/dev/storage',
        }).then(r => {
            setFilesList(r.data.files)
        })
    }


    return (<div className={'TablePage'}>
        <div className={'PageCenter'}>
            <div className={'table-container'}>
                <table>
                    <thead>
                    <tr>
                        <th>nom</th>
                        <th>taille</th>
                        <th>création</th>
                        <th/>
                        <th/>
                    </tr>
                    </thead>
                    <tbody>
                    {filesList && filesList.map(f =>
                        <tr>
                            <td>{f.name}</td>
                            <td>{f.size}</td>
                            <td>{f.creation}</td>
                            <td><a className={'btn'} href={'/storage/logs/'+ f.name + '.zip'} target={"_blank"}><img alt={''} src={'/assets/images/documents.png'}/> </a> </td>
                            <td><button onClick={()=>{deleteFile(f.name)}} className={'btn'} ><img alt={''} src={'/assets/images/decline.png'}/> </button> </td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>

    </div>)
}

export default DevStorage;
