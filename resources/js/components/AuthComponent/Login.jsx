import React, {useEffect, useState} from 'react';
import {NavLink, Redirect} from "react-router-dom";
import axios from "axios";
import {useNotifications} from "../context/NotificationProvider";
import {v4} from "uuid";


const Login = (props) => {
    const dispatch = useNotifications();
    const [image, setImage] = useState('')


    const updateUserImage = async () => {
        await axios({
            method: 'GET',
            url: '/data/bg'
        }).then(r => {
            setImage(r.data.image)
        })
    }

    useEffect(() => {
        updateUserImage()
        if (errors !== "") {

            dispatch({
                type: 'ADD_NOTIFICATION',
                payload: {
                    id: v4(),
                    type: 'danger',
                    message: errors
                }
            });

      }
    }, [])

        return (
            <div className={'Auth'}  style={{backgroundImage: 'url('+image+')'}}>
                <div className={'Authentifier'}>
                    <div className={'auth-header'}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    </div>
                    <div className={'auth-content'}>
                        <h1>Connexion</h1>
                        <a href="/auth/redirect" className="btn --big">
                            Avec discord
                            <img src={'/assets/images/discord.png'} alt={''}/>
                        </a>
                    </div>
                    <div className={'auth-footer'}>
                            <NavLink className={'btn --medium'} to={'/register'} >j'ai pas de compte</NavLink>
                    </div>
                </div>
            </div>
        )
}

export default Login;
