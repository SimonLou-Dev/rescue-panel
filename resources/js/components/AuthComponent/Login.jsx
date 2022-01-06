import React from 'react';
import {NavLink, Redirect} from "react-router-dom";
import axios from "axios";

const Login = (props) => {

        return (
            <div className={'Auth'}>
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
