import React from 'react';
import {Link} from "react-router-dom";


class GetInfos extends React.Component {
    render() {
        return (
            <div className={'Register'}>
                <div className={'Form'}>
                    <form method={'POST'} onSubmit={this.registering}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                        <h1>Informations</h1>
                        <label>Contré habité : </label>
                        <select defaultValue={1}>
                            <option value={1} disabled>choisir</option>
                            <option>LS</option>
                            <option>BC</option>
                        </select>
                        {this.state.pseudo_error &&
                        <div className={'form-error'}>
                            <p>La case est vide (min 5 caractères) </p>
                        </div>
                        }
                        <label>n° de tem IG : </label>
                        <input type={'number'} value={this.state.email} name={'email'} onChange={this.EmailCheck}/>
                        {this.state.email_empty &&
                        <div className={'form-error'}>
                            <p>La case est vide (min 5 caractères)</p>
                        </div>
                        }
                        {this.state.email_exist &&
                        <div className={'form-error'}>
                            <p>Cette adresse mail est déja utilisée</p>
                        </div>
                        }
                        <label>n° de compte </label>
                        <input type={'number'} value={this.state.psw} name={'psw'} onChange={this.PswCheck}/>
                        {this.state.psw_error &&
                        <div className={'form-error'}>
                            <p>Mot de passe vide (min 5 caractères)</p>
                        </div>
                        }
                        <label>Fuseau horaire : </label>
                        <select  defaultValue={1} name={'psw_repeat'} onChange={this.PswRepeatCheck}>
                            <option value={1} disabled>choisir</option>
                            <option>[FR] Paris - GMT+1</option>
                            <option>[NY] New York - UTC-5</option>
                        </select>
                        {this.state.repeat_error &&
                        <div className={'form-error'}>
                            <p>Les deux mots de passe ne correspondent pas</p>
                        </div>
                        }
                        <div className={'btn-contain'}>
                            <Link className={'btn'} to={'/login'} >j'ai déja un compte</Link>
                            <button type={'submit'} className={'btn'}>S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        )
    };
}

export default GetInfos;
