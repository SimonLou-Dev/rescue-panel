import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import Uploader from "../props/utils/Uploader";


class Stats extends React.Component {
    render() {
        return (
            <div className="acc-content">

            </div>
        );
    }
}

class Account extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            data: false,
            email: '',
            name: '',
            compte: '0000',
            tel: '00000000',
            liveplace: 'BC',
            popup:false,
            mdp: '',
            mdprepet: '',
            lastmdp: '',
            image: null,
        }

        this.postInfos = this.postInfos.bind(this);
        this.changeMdp = this.changeMdp.bind(this)
        this.postBg = this.postBg.bind(this);
    }


    async componentDidMount() {
        var req = await axios({
            url: '/data/user/infos/get',
            method: 'get',
        })
        if(req.status === 200){
            this.setState({
                data:true,
                email: req.data.user.email,
                name: req.data.user.name,
                compte: req.data.user.compte,
                tel: req.data.user.tel,
                liveplace: req.data.user.liveplace,
            });
        }
    }

    async postInfos(e){
        e.preventDefault()
        await  axios({
            url: '/data/user/infos/put',
            method:'PUT',
            data: {
                email: this.state.email,
                name: this.state.name,
                compte: this.state.compte,
                tel: this.state.tel,
                liveplace: this.state.liveplace,
            }
        })
    }

    async changeMdp(e){
        e.preventDefault();
        var req = await axios({
            url: '/data/user/mdp/put',
            method: 'put',
            data: {
                last: this.state.lastmdp,
                newmdp: this.state.mdp,
                mdprepet: this.state.mdprepet,
            }
        })
        if(req.status === 201){
            this.setState({
                lastmdp: '',
                mdp: '',
                mdprepet: '',
                popup: false,
            })
        }
    }

    async postBg(){
        var req = await axios({
            method:'POST',
            url: '/data/user/bg/post',
            data: {
                image: this.state.image,
            }
        })
    }

    render() {
        return (
            <div className="acc-content">
                {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                }
                {this.state.data &&
                    <section className="changedata" style={{filter: this.state.popup ? 'blur(5px)' : 'none'}}>
                    <form onSubmit={this.postInfos}>
                        <div className="rowed">
                            <label>pseudo</label>
                            <input required type={"text"} value={this.state.name} onChange={(e)=>{this.setState({name:e.target.value})}}/>
                        </div>
                        <div className="rowed">
                            <label>email</label>
                            <input required type={"email"} value={this.state.email} onChange={(e)=>{this.setState({email:e.target.value})}}/>
                        </div>
                        <div className="rowed">
                            <label>numéro de tel</label>
                            <input required type={"number"} max={'99999999'} value={this.state.tel} onChange={(e)=>{this.setState({tel:e.target.value})}}/>
                        </div>
                        <div className="rowed">
                            <label>numéro de compte</label>
                            <input required type={"number"} value={this.state.compte} onChange={(e)=>{this.setState({compte:e.target.value})}}/>
                        </div>
                        <div className="rowed">
                            <label>Conté habité</label>
                            <select value={this.state.liveplace} onChange={(e)=>{this.setState({liveplace:e.target.value})}}>
                                <option>BC</option>
                                <option>LS</option>
                            </select>
                        </div>
                        <button type={'submit'} className={'btn'}>valider</button>
                    </form>
                </section>
                }
                {this.state.data &&
                    <section className={'bigchange'} style={{filter: this.state.popup ? 'blur(5px)' : 'none'}} >
                    <button className={'btn'} onClick={()=>this.setState({popup:true})}>changer de mot de passe</button>
                    <div className="img">
                        <div className="rowed">
                            <h2>Arrière plan du site (Affeté à la prochainne connexion)</h2>
                            <div className="beta"/>
                        </div>
                        <Uploader text={'1920*1080 2MO'} images={(image)=>{
                            this.setState({image:image});
                            this.postBg();
                        }}/>
                    </div>
                </section>
                }


                {this.state.popup &&
                    <section className={'popup'}>
                    <div className={'center'}>
                        <form onSubmit={this.changeMdp}>
                            <h1>Changer de mot de passe</h1>
                            <div className={'row'}>
                                <label>Ancien mot de passe</label>
                                <input type={'password'} value={this.state.lastmdp} onChange={(e)=>{this.setState({lastmdp:e.target.value})}}/>
                            </div>
                            <div className={'row'}>
                                <label>Nouveau mot de passe</label>
                                <input type={'password'} value={this.state.mdp} onChange={(e)=>{this.setState({mdp:e.target.value})}}/>
                            </div>
                            <div className={'row'}>
                                <label>Répéter le mot de passe</label>
                                <input type={'password'} value={this.state.mdprepet} onChange={(e)=>{this.setState({mdprepet:e.target.value})}}/>
                            </div>
                            <div className={'row-evenly'}>
                                <button className={'btn'} onClick={()=>this.setState({popup:false})}>Fermer</button>
                                <button className={'btn'} type={'submit'}>Envoyer</button>
                            </div>
                        </form>
                    </div>
                </section>
                }
            </div>
        );
    }
}

class MonCompte extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            stats: false,
            account: true
        }
    }
    // Btn des stats <button onClick={()=> this.setState({stats: true, account: false})} className={this.state.stats ? '' : 'unselected'}><img src={'/assets/images/stats.svg'} alt={''}/>mes statistiques</button>
    render() {
        return (
            <div className={"moncompte"}>
                <PagesTitle title={"Mon Compte <br> <span>Resident BC</span>"}/>
                <div className={'account-container'}>
                    <div className={'header'}>
                        <button onClick={()=> this.setState({stats: false, account: true})} className={this.state.account ? '' : 'unselected'}><img src={'/assets/images/settings.svg'} alt={''}/> mes informations</button>
                    </div>
                    {this.state.stats && <Stats/>}
                    {this.state.account && <Account/>}
                </div>
            </div>
        )
    }
}

export default MonCompte;
