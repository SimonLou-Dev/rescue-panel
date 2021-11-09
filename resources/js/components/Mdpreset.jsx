import React from 'react';
import Uploader from "./props/utils/Uploader";
import {NavLink, Redirect} from "react-router-dom";
import axios from "axios";

class Mdpreset extends React.Component {
    constructor(props) {
        super(props);
        this.state= {
            redirect: '',
            psw: '',
            pswrep: '',
            errors: [],
        }
    }

    render() {
        if(this.state.redirect){
            return (<Redirect to={this.state.redirect}/>);
        }
        return (
            <div className={'Login'}>
                <div className={'Form'}>
                    <form method={"POST"} onSubmit={async (e) => {
                        e.preventDefault();
                        await axios({
                            method: 'POST',
                            url: '/data/user/reset/post',
                            data: {
                                psw: this.state.psw,
                                pswrep: this.state.pswrep
                            }
                        }).then(response => {
                            if (response.status === 201){
                                this.setState({redirect: response.data.redirect});
                            }
                        })
                    }}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                        <h1>Réinitialisation de mot de passe</h1>
                        <label>Mot de passe : </label>
                        <input value={this.state.psw} type={'password'} name={'psw'} onChange={e=>{this.setState({psw:e.target.value})}}/>
                        <label>Mot de passe : </label>
                        <input value={this.state.pswrep} type={'password'} name={'psw'} onChange={e=>{this.setState({pswrep:e.target.value})}}/>
                        <div className={'btn-contain'}>
                            <NavLink className={'btn'} to={'/register'} >j'ai pas de compte</NavLink>
                            <button type={'submit'} className={'btn'}>Se connecter</button>
                        </div>
                    </form>

                </div>
            </div>
        )
    }
}

export default Mdpreset;
