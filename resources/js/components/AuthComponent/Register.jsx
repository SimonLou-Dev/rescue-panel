import React, {useEffect, useState} from 'react';
import {NavLink, Redirect} from "react-router-dom";
import axios from "axios";

const Register = (props) => {
    const [image, setImage] = useState('')

    useEffect(() => {
        updateUserImage()
    }, [])

    const updateUserImage = async () => {
        await axios({
            method: 'GET',
            url: '/data/bg'
        }).then(r => {
            setImage(r.data.image)
        })
    }

        return (
            <div className={'Auth'}  style={{backgroundImage: 'url('+image+')'}}>
                <div className={'Authentifier'}>
                    <div className={'auth-header'}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    </div>
                    <div className={'auth-content'}>
                        <h1>Inscription</h1>
                        <a href="/auth/redirect" className="btn --big">
                            Avec discord
                            <img src={'/assets/images/discord.png'} alt={''}/>
                        </a>
                    </div>
                    <div className={'auth-footer'}>
                            <NavLink className={'btn --medium'} to={'/login'} >j'ai un compte</NavLink>
                    </div>
                </div>
            </div>
        )
}

export default Register;
