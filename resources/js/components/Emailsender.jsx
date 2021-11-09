import React from 'react';
import {useNotifications} from "./context/NotificationProvider";
import {v4} from "uuid";
import axios from "axios";
import {useState} from "react";


/*

 */

export function Emailsender() {
    const dispatch = useNotifications()
    const [email, setEmail] = useState('');


    return (
        <div className={'Login'}>
            <div className={'Form'}>
                <form method={"POST"} onSubmit={async (e) => {
                    e.preventDefault();
                    await axios({
                        method: 'GET',
                        url: '/data/user/reset/send/' + email,
                    }).then(response => {
                        if (response.status === 200) {
                            dispatch({
                                type: 'ADD_NOTIFICATION',
                                payload: {
                                    id: v4(),
                                    type: 'success',
                                    message: 'email envoyé'
                                }
                            });
                        } else {
                            dispatch({
                                type: 'ADD_NOTIFICATION',
                                payload: {
                                    id: v4(),
                                    type: 'warning',
                                    message: 'une erreur est survenue'
                                }
                            });
                        }
                    }).catch(error => {
                        dispatch({
                            type: 'ADD_NOTIFICATION',
                            payload: {
                                id: v4(),
                                type: 'warning',
                                message: 'L\'email entrée n\'est pas reconnue',
                            }
                        });
                    })
                }}>
                    <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    <h1>Réinitialisation de mot de passe</h1>
                    <label>Email : </label>
                    <input value={email} type={'text'} name={'email'} onChange={e => {
                        setEmail(e.target.value)
                    }}/>
                    <button type={'submit'} className={'btn'}>Valider</button>
                </form>

            </div>
        </div>
    )

}

export default Emailsender;
