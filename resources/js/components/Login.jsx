import React from 'react';
import {NavLink, Redirect} from "react-router-dom";
import axios from "axios";

class Login extends React.Component {

    constructor(props) {
        super(props);
        this.state= {
            error: true,
            email: "",
            psw: "",
            empty_email: false,
            empty_psw: false,
            email_error: false,
            credential_error:false,
            redirect: null,
        };
        this.EmailChange = this.EmailChange.bind(this);
        this.PswChange = this.PswChange.bind(this);
        this.Submited= this.Submited.bind(this);
    }

    componentDidMount() {
        setTimeout(() => {
            if(this.state.email < 5){
                this.setState({empty_email: true, error: true})
            }else{
                this.setState({empty_email: false})
                if(!this.state.credential_error && !this.state.empty_psw){
                    this.setState({error: false});
                }
            }
            if(this.state.psw < 5){
                this.setState({empty_psw: true, error: true})
            }else{
                this.setState({empty_psw: false})
                if(!this.state.email_error && !this.state.empty_email){
                    this.setState({error: false});
                }
            }

        },200);

    }


    EmailChange(e){
        e.preventDefault();
        this.setState({email: e.target.value});
        this.setState({email_error:false});
        if(e.target.value < 5){
            this.setState({empty_email: true, error: true})
        }else{
            this.setState({empty_email: false})
            if(!this.state.credential_error && !this.state.empty_psw){
                this.setState({error: false});
            }
        }

    }
    PswChange(e){
        e.preventDefault();
        this.setState({psw: e.target.value})
        this.setState({credential_error:false});
        if(e.target.value< 5){
            this.setState({empty_psw: true, error: true})
        }else{
            this.setState({empty_psw: false})
            if(!this.state.email_error && !this.state.empty_email){
                this.setState({error: false});
            }
        }
    }
    async Submited(e){
        e.preventDefault();
        if(!this.state.error){
            var email = this.state.email;
            var psw = this.state.psw;
            var req = await axios({
                method: 'post',
                url: '/data/login',
                data: {
                  'email': email,
                  'psw': psw,
                  'X-CSRF-TOKEN': csrf,
                },
            });
            if(req.data.status === 'Mot de passe invalide'){
                this.setState({credential_error: true, error: true})
            }
            if(req.data.status === 'adresse mail non existante'){
                this.setState({email_error: true, error: true})
            }
            if(req.data.status === 'ANA'){
               window.location.href = "/ANA";
            }
            if(req.data.status === 'INFOS'){
                window.location.href = "/informations";
            }
            if(req.data.status === 'OK'){
                window.location.href = "/";
            }
        }
    }

    render() {
        if(this.state.redirect){
            return (<Redirect to={this.state.redirect}/>);
        }
        return (
            <div className={'Login'}>
                <div className={'Form'}>
                    <form method={"POST"} onSubmit={this.Submited}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                        <h1>Connexion</h1>
                        <label>adresse mail : </label>
                        <input value={this.state.email} type={'email'} name={'email'} onChange={this.EmailChange}/>
                        {this.state.email_empty &&
                            <div className={'form-error'}>
                                <p>Case vide (min 5 caractères)</p>
                            </div>
                        }
                        {this.state.email_error &&
                        <div className={'form-error'}>
                            <p>cette email n'existe pas</p>
                        </div>
                        }
                        <label>Mot de passe : </label>
                        <input value={this.state.psw} type={'password'} name={'psw'} onChange={this.PswChange}/>
                        {this.state.empty_psw &&
                        <div className={'form-error'}>
                            <p>Case vide (min 5 caractères)</p>
                        </div>
                        }
                        {this.state.credential_error &&
                        <div className={'form-error'}>
                            <p>mot de passe incorect</p>
                        </div>
                        }
                        <div className={'btn-contain'}>
                            <NavLink className={'btn'} to={'/register'} >inscription</NavLink>
                            <NavLink className={'btn'} to={'/sendmail'} >mot de passe perdu</NavLink>
                            <button type={'submit'} className={'btn'}>Se connecter</button>
                        </div>
                    </form>

                </div>
            </div>
        )
    }
}

export default Login;
