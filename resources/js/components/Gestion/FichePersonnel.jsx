import React from 'react';
import PagesTitle from "../props/utils/PagesTitle";
import {Link} from "react-router-dom";
import {rootUrl} from "../props/Gestion/Content/ContentCard";
import PermsContext from "../context/PermsContext";
import axios from "axios";
import dateFormat from "dateformat";

class FichePersonnel extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            user_id: null,
            blur : false,
            material: false,
            sanction: false,
            sanc: "1",
            userinfos: null,
            notes:null,
            sanctions:null,
            materiallist:null,
            materialform: {
                kevlar: false,
                lampe: false,
                flare: false,
                flareGun: false,
                extincteur:false,
            },
            sanctionform: {
                raison: '',
                map_date: '',
                map_time: '',
                note_lic: ''
            }
        }
        this.getnotes = this.getnotes.bind(this)
        this.getsanctions = this.getsanctions.bind(this)
        this.getmaterial = this.getmaterial.bind(this)
        this.postmaterial = this.postmaterial.bind(this)
        this.sendsanction = this.sendsanction.bind(this)

    }

    async componentDidMount() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const id = urlParams.get('id')
        await axios({
            method: 'get',
            url: '/data/usersheet/' + id + '/infos'
        }).then(response => {
            this.setState({userinfos: response.data.infos, user_id: id})
        })

        this.getmaterial(id);
        this.getnotes(id);
        this.getsanctions(id);


    }
    /*

     */

    async getsanctions(id){
        await axios({
            method: 'get',
            url: '/data/usersheet/' + id + '/sanctions'
        }).then(response => {
            this.setState({sanctions: response.data.sanctions})
        })
    }
    async getmaterial(id){
        await axios({
            method: 'get',
            url: '/data/usersheet/' + id + '/material'
        }).then(response => {
            var material = Object.entries(response.data.material)

            this.setState({materiallist: material, materialform: response.data.material})
        })
    }
    async getnotes(id){
        await axios({
            method: 'get',
            url: '/data/usersheet/' + id + '/note'
        }).then(response => {
            this.setState({notes: response.data.note})
        })
    }

    async postmaterial(e){
        e.preventDefault();
        await  axios({
            method: 'put',
            url: '/data/usersheet/'+ this.state.user_id + '/material',
            data: {
                material: this.state.materialform
            }
        }).then(response => {
            this.getmaterial(this.state.user_id)
            this.setState({blur: false, material: false})
        })

    }

    async sendsanction(e){
        e.preventDefault()
        await axios({
            method: 'post',
            url: '/data/usersheet/'+ this.state.user_id + '/sanctions',
            data: {
                sanctions : this.state.sanc,
                infos: this.state.sanctionform
            }
        }).then(response => {
            this.setState({
                sanctionform: {
                    raison: '',
                    map_date: '',
                    map_time: '',
                    note_lic: ''
                },blur: false, sanction: false
            })
            this.getsanctions(this.state.user_id)
        })

    }


    render() {
        const perm = this.context;
        return (
            <div className={'FichePersonnel'} >
                <section className={'header'} style={{filter: this.state.blur? 'blur(5px)' : 'none'}} >
                    <Link to={'/gestion/personnel'} className={'btn'}>retour</Link>
                    <a className={'btn'}>exporter</a>
                    <PagesTitle title={'Fiche Personnel'}/>
                    <button className={'btn'}>déclarer la démission</button>
                </section>
                <section className={'content'} style={{filter: this.state.blur? 'blur(5px)' : 'none'}} >
                    <div className={'infos'}>
                        <div className={'infoCat'}>
                            <h2>Information entreprise</h2>
                                {this.state.userinfos !== null &&
                                    <div className={'infoList'}>
                                        <h4><span>Personnel : </span> {this.state.userinfos.name}</h4>
                                        <h4><span>Inscrit le : </span> {dateFormat(this.state.userinfos.created_at, 'dd/mm/yyyy')}</h4>
                                        <h4><span>Grade actuel : </span> {this.state.userinfos.get_grade.name}</h4>
                                        <h4><span>Matricule : </span> {(this.state.userinfos.matricule === null ? 'null': this.state.userinfos.matricule)}</h4>
                                    </div>
                                }
                             </div>
                        <div className={'infoCat'}>
                            <h2>Information perso</h2>
                            {this.state.userinfos !== null &&
                                <div className={'infoList'}>
                                    <h4><span>Lieux de résidence : </span> {this.state.userinfos.liveplace}</h4>
                                    <h4><span>N° de téléphone : </span> {this.state.userinfos.tel}</h4>
                                    <h4><span>N° de compte : </span> {this.state.userinfos.compte}</h4>
                                    <h4><span>Discord id : </span>{(this.state.userinfos.discord_id === null ? 'null': this.state.userinfos.discord_id)}</h4>
                                </div>

                            }
                        </div>


                    </div>

                    <div className={'sanctions'}>
                        <div className={'heading'}>
                            <h1>Liste des sanctions</h1>
                            <button className={'btn'} onClick={() => {
                                this.setState({
                                    blur: true,
                                    sanction: true
                                })
                            }}>Ajouter</button>
                        </div>
                        <ul className={'sanctionsListe'}>
                            {this.state.sanctions !== null && this.state.sanctions.map((one) =>
                                <li>
                                    <h4><span>Type : </span> {one.type}</h4>
                                    <h4><span>Prononcé le : </span> {one.prononcedam}</h4>
                                    <h4><span>Prononcé par : </span> {one.prononcedby}</h4>
                                    <h4><span>Raison : </span> {one.raison} </h4>
                                    {one.type === "Mise à pied" &&
                                        <h4><span>Durée : </span>  {one.diff} </h4>
                                    }
                                    {one.type === "Mise à pied" &&
                                        <h4><span>Fin : </span>  {one.ended_at} </h4>
                                    }
                                    {one.type === "Dégradation" &&
                                    <h4><span>Infos : </span>  {one.ungrad} </h4>
                                    }
                                    {one.type === "Exclusion" &&
                                    <h4><span>Info : </span>  {one.noteLic} </h4>
                                    }

                                </li>
                            )

                            }
                        </ul>
                    </div>
                    <div className={'notes'}>
                        <h1>Notes</h1>
                        <ul className={'notelist'}>
                            <li className={'note'}>
                                <button disabled={(perm.membersheet_note!== 1)} className={'btn deleter'} ><img src={rootUrl + 'assets/images/cancel.png'}/></button>
                                <h4><span>Ecrit par :</span> jean claude</h4>
                                <h4><span>Date :</span> 15/05/2012</h4>
                                <h4>Gratis mortem rare falleres navis est. A falsis, lamia barbatus hydra.</h4>
                            </li>
                        </ul>
                        <div className={'noteadder'}>
                            <form>
                                <textarea placeholder={'Ecrire une note...'} disabled={(perm.membersheet_note!== 1)}/>
                                <button type={'submit'} disabled={(perm.membersheet_note!== 1)} className={'btn'}>valider</button>
                            </form>
                        </div>
                    </div>
                    <div className={'material'}>
                        <div className="material-head">
                            <h1>Matériel attribué :</h1>
                            <button className={'btn'} disabled={(perm.modify_material!== 1)} onClick={() => {
                                this.setState({
                                    blur: true,
                                    material: true
                                })
                            }}>modifier</button>
                        </div>
                        <div className="material-list">
                            {this.state.materiallist !==  null &&
                                this.state.materiallist.map((material) =>
                                    material[1] === true &&
                                        <div className={'material-item'} key={material[0]}>
                                            <h4>{material[0]}</h4>
                                        </div>
                                )
                            }
                        </div>
                    </div>
                </section>
                {this.state.material === true &&

                    <div className={'material-modifier'}>
                    <div className={'popup'}>
                        <h1>Modification du matériel</h1>
                        <form onSubmit={this.postmaterial}>
                            <div className={'form-content'}>
                                <div className={'column'}>
                                    <div className={'item'}>
                                        <input type={'checkbox'} checked={this.state.materialform.kevlar} onChange={(e) => {this.setState({materialform: {
                                                kevlar: !this.state.materialform.kevlar,
                                                lampe: this.state.materialform.lampe,
                                                flare: this.state.materialform.flare,
                                                flareGun: this.state.materialform.flareGun,
                                                extincteur: this.state.materialform.extincteur,

                                        }})}}/>
                                        <label>Kevlar</label>
                                    </div>
                                    <div className={'item'}>
                                        <input type={'checkbox'} checked={this.state.materialform.lampe} onChange={(e) => {this.setState({materialform: {
                                                kevlar: this.state.materialform.kevlar,
                                                lampe: !this.state.materialform.lampe,
                                                flare: this.state.materialform.flare,
                                                flareGun: this.state.materialform.flareGun,
                                                extincteur: this.state.materialform.extincteur,

                                            }})}}/>
                                        <label>lampe</label>
                                    </div>
                                    <div className={'item'}>
                                        <input type={'checkbox'} checked={this.state.materialform.flare} onChange={(e) => {this.setState({materialform: {
                                            kevlar: this.state.materialform.kevlar,
                                            lampe: this.state.materialform.lampe,
                                            flare: !this.state.materialform.flare,
                                            flareGun: this.state.materialform.flareGun,
                                            extincteur: this.state.materialform.extincteur,

                                        }})}}/>
                                        <label>flare</label>
                                    </div>
                                </div>
                                <div className={'column'}>
                                    <div className={'item'}>
                                        <input type={'checkbox'} checked={this.state.materialform.flareGun} onChange={(e) => {this.setState({materialform: {
                                            kevlar: this.state.materialform.kevlar,
                                            lampe: this.state.materialform.lampe,
                                            flare: this.state.materialform.flare,
                                            flareGun: !this.state.materialform.flareGun,
                                            extincteur: this.state.materialform.extincteur,

                                        }})}}/>
                                        <label>flare gun</label>
                                    </div>
                                    <div className={'item'}>
                                        <input type={'checkbox'} checked={this.state.materialform.extincteur} onChange={(e) => {this.setState({materialform: {
                                            kevlar: this.state.materialform.kevlar,
                                            lampe: this.state.materialform.lampe,
                                            flare: this.state.materialform.flare,
                                            flareGun: this.state.materialform.flareGun,
                                            extincteur: !this.state.materialform.extincteur,
                                        }})}}/>
                                        <label>extincteur</label>
                                    </div>
                                </div>
                            </div>
                            <div className={'footer'}>
                                <button type={"submit"} className={'btn'}>Valider</button>
                                <button className={'btn'} onClick={() => {
                                    this.setState({
                                        blur: false,
                                        material: false,
                                    })
                                }}>Fermer</button>
                            </div>

                        </form>
                    </div>
                </div>

                }
                {this.state.sanction === true &&
                    <div className={'sanctions-adder'}>
                    <div className={'popup'}>
                        <form onSubmit={this.sendsanction}>
                            <h1>Mettre une sanction</h1>
                            <div className={'form-content'}>
                                <div className={'item border'}>

                                    <select onChange={(e)=>{this.setState({sanc: e.target.value})}} value={this.state.sanc}>
                                        <option value={1} disabled={(perm.sanction_warn !== 1)}>Avertissement</option>
                                        <option value={2} disabled={(perm.sanction_MAP !== 1)}>Mise à pied</option>
                                        <option value={3} disabled={(perm.sanction_degrade !== 1)}>Dégrader</option>
                                        <option value={4} disabled={(perm.sanction_exclu !== 1)}>Exclure</option>
                                    </select>
                                </div>
                                {this.state.sanc === "2" &&
                                <div className={'item'}>
                                    <label>Jusqu'au :</label>
                                    <input type={'date'} onChange={ e => {
                                        this.setState({ sanctionform: {
                                                raison: this.state.sanctionform.raison,
                                                map_date: e.target.value,
                                                map_time: this.state.sanctionform.map_time,
                                                note_lic: this.state.sanctionform.note_lic,
                                    }})}}/>
                                    <input type={'time'} onChange={ e => {
                                        this.setState({ sanctionform: {
                                                raison: this.state.sanctionform.raison,
                                                map_date: this.state.sanctionform.map_date,
                                                map_time: e.target.value,
                                                note_lic: this.state.sanctionform.note_lic,
                                            }})}}/>
                                </div>
                                }
                                {this.state.sanc === "4" &&
                                <div className={'item'}>
                                    <label>note du licenciement :</label>
                                    <textarea placeholder={'(sans préavis, ni indemnité de licenciement, ni prime, ni salaire)'} onChange={ e => {
                                        this.setState({ sanctionform: {
                                                raison: this.state.sanctionform.raison,
                                                map_date: this.state.sanctionform.map_date,
                                                map_time: this.state.sanctionform.map_time,
                                                note_lic: e.target.value,
                                            }})}}/>
                                </div>
                                }
                                <div className={'item col'}>
                                    <label>Raison</label>
                                    <textarea onChange={ e => {
                                        this.setState({ sanctionform: {
                                                raison: e.target.value,
                                                map_date: this.state.sanctionform.map_date,
                                                map_time: this.state.sanctionform.map_time,
                                                note_lic: this.state.sanctionform.note_lic,
                                            }})}}/>/>
                                </div>
                            </div>
                            <div className={'footer'}>
                                <button type={"submit"} className={'btn'}>Valider</button>
                                <button className={'btn'} onClick={() => {
                                    this.setState({
                                        blur: false,
                                        sanction: false,
                                    })
                                }}>Fermer</button>
                            </div>
                        </form>
                    </div>

                </div>
                }

            </div>
        )
    };
}
FichePersonnel.contextType = PermsContext;
export default FichePersonnel;
