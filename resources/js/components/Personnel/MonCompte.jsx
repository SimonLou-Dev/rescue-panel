import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";


class Stats extends React.Component {
    render() {
        return (
            <div className="acc-content">

            </div>
        );
    }
}

class Account extends React.Component {
    render() {
        return (
            <div className="acc-content">
                <section className="changedata">
                    <div className="rowed">
                        <label>pseudo</label>
                        <input type={"text"}/>
                    </div>
                    <div className="rowed">
                        <label>email</label>
                        <input type={"text"}/>
                    </div>
                    <div className="column">
                        <div className="rowed">
                            <label>fuseau horaire</label>
                            <select>
                                <option>Paris - GMT+1</option>
                                <option>New York - UTC-5</option>
                            </select>
                        </div>
                        <label className={'info'}>prend effet à la prochaine connexion</label>
                    </div>
                    <div className="rowed">
                        <label>numéro de tel</label>
                        <input type={"number"} max={'99999999'}/>
                    </div>
                    <div className="rowed">
                        <label>numéro de compte</label>
                        <input type={"number"}/>
                    </div>
                </section>
                <section className={'bigchange'}>
                    <button className={'btn'}>changer de mot de passe</button>
                    <div className="img">
                        <div className="rowed">
                            <h2>Arrière plan du site</h2>
                            <div className="beta"/>
                        </div>
                        <form>
                            <div className="rowed">
                                <label>image</label>
                                <input type={'file'}/>
                            </div>
                            <button className={'btn right'}type={'submit'}>ajouter</button>
                            <div className="rowed">
                                <h4>1920x1080 - max 10Mo</h4>
                                <button className={'btn'}>supprimer</button>
                            </div>
                        </form>
                    </div>
                </section>
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

    render() {
        return (
            <div className={"moncompte"}>
                <PagesTitle title={"Mon Compte <br> <span>Resident BC</span>"}/>
                <div className={'account-container'}>
                    <div className={'header'}>
                        <button onClick={()=> this.setState({stats: false, account: true})} className={this.state.account ? '' : 'unselected'}><img src={'/assets/images/settings.svg'} alt={''}/> mes informations</button>
                        <button onClick={()=> this.setState({stats: true, account: false})} className={this.state.stats ? '' : 'unselected'}><img src={'/assets/images/stats.svg'} alt={''}/>mes statistiques</button>
                    </div>
                    {this.state.stats && <Stats/>}
                    {this.state.account && <Account/>}
                </div>
            </div>
        )
    }
}

export default MonCompte;
