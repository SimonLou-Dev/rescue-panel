import React from 'react';
import axios from 'axios';
import {Link} from "react-router-dom";
import { Redirect } from "react-router-dom";
export const rootUrl = document.querySelector('body').getAttribute('data-root-url');

class Register extends React.Component {
    constructor(props) {
        super(props);
        this.registering = this.registering.bind(this);
        this.PswRepeatCheck = this.PswRepeatCheck.bind(this);
        this.PswCheck = this.PswCheck.bind(this);
        this.EmailCheck = this.EmailCheck.bind(this);
        this.PseudoCheck = this.PseudoCheck.bind(this);
        this.state = {
            pseudo: "",
            email: "",
            psw: "",
            repeat_psw: "",
            repeat_error: false,
            psw_error: false,
            pseudo_error: false,
            email_empty: false,
            error: true,
            email_exist: false,
            redirect: false,
        }
    }

    componentDidMount() {
        setTimeout(() => {
            if (this.state.repeat_psw !== this.state.psw){
                this.setState({repeat_error: true, error: true});
            }else{
                this.setState({repeat_error: false});
                if(!this.state.psw_error && !this.state.email_empty && !this.state.pseudo_error){
                    this.setState({error: false})
                }
            }
            if (this.state.psw < 5){
                this.setState({psw_error: true, error: true});
            }else{
                this.setState({psw_error: false});
                if(!this.state.repeat_error && !this.state.email_empty && !this.state.pseudo_error){
                    this.setState({error: false})
                }
            }
            if (this.state.email < 5){
                this.setState({email_empty: true, error: true});
            }else{
                this.setState({email_empty: false});
                if(!this.state.repeat_error && !this.state.psw_error && !this.state.pseudo_error){
                    this.setState({error: false})
                }
            }
            if (this.state.pseudo < 5){
                this.setState({pseudo_error: true, error: true});
            }else{
                this.setState({pseudo_error: false});
                if(!this.state.repeat_error && !this.state.psw_error && !this.state.email_empty){
                    this.setState({error: false})
                }
            }
        }, 200);
    }

    PswRepeatCheck(e){
        this.setState({repeat_psw: e.target.value})
        if (e.target.value !== this.state.psw){
            this.setState({repeat_error: true, error: true});
        }else{
            this.setState({repeat_error: false});
            if(!this.state.psw_error && !this.state.email_empty && !this.state.pseudo_error){
                this.setState({error: false})
            }
        }

    }
    PswCheck(e){
        this.setState({psw: e.target.value});
        if (e.target.value < 5){
            this.setState({psw_error: true, error: true});
        }else{
            this.setState({psw_error: false});
            if(!this.state.repeat_error && !this.state.email_empty && !this.state.pseudo_error){
                this.setState({error: false})
            }
        }
    }
    EmailCheck(e){
        this.setState({email: e.target.value});
        if (e.target.value.length < 5){
            this.setState({email_empty: true, error: true});
        }else{
            this.setState({email_empty: false});
            if(!this.state.repeat_error && !this.state.psw_error && !this.state.pseudo_error){
                this.setState({error: false})
            }
        }
        if(this.state.email_exist){
            this.setState({email_exist:false, error: false});
        }
    }
    PseudoCheck(e){
        this.setState({pseudo: e.target.value});
        if (e.target.value.length < 5){
            this.setState({pseudo_error: true, error: true});
        }
        else{
            this.setState({pseudo_error: false});
            if(!this.state.repeat_error && !this.state.psw_error && !this.state.email_empty){
                this.setState({error: false})
            }
        }
    }

    async registering(e) {
        e.preventDefault();
        if(!this.state.error) {
            var email = this.state.email;
            var psw = this.state.psw;
            var pseudo = this.state.pseudo;
            const res = await axios({
                method: 'POST',
                url: '/data/register',
                data: {
                    'pseudo': pseudo,
                    'psw': psw,
                    'email': email,
                    'X-CSRF-TOKEN': csrf,
                }
            });
            if(res.data['raison'] === "Email taken"){
                this.setState({email_exist: true, error: true});
            }

            if(res.status === 201){
                window.location.href = "/informations";
            }
        }
    }

    render() {
        if(this.state.redirect){
            return (<Redirect to={this.state.redirect}/>);
        }
        return (
            <div className={'Register'}>
               <div className={'Form'}>
                  <form method={'POST'} onSubmit={this.registering}>
                      <img alt={""} src={rootUrl + 'assets/images/LONG_EMS_BC_2.png'}/>
                      <h1>Inscription</h1>
                      <label>nom prénom : </label>
                      <input type={'text'} value={this.state.pseudo} maxLength={"20"} onChange={this.PseudoCheck}/>
                      {this.state.pseudo_error &&
                        <div className={'form-error'}>
                            <p>La case est vide (min 5 caractères) </p>
                        </div>
                      }
                      <label>adresse mail : </label>
                      <input type={'email'} value={this.state.email} name={'email'} onChange={this.EmailCheck}/>
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
                      <label>Mot de passe : </label>
                      <input type={'password'} value={this.state.psw} name={'psw'} onChange={this.PswCheck}/>
                      {this.state.psw_error &&
                      <div className={'form-error'}>
                          <p>Mot de passe vide (min 5 caractères)</p>
                      </div>
                      }
                      <label>Confirmation mot de passe : </label>
                      <input type={'password'} value={this.state.repeat_psw} name={'psw_repeat'} onChange={this.PswRepeatCheck}/>
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
    }
}

export default Register;
