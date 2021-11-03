import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import {NavLink} from "react-router-dom";
import PermsContext from "../context/PermsContext";
import Rapport from "./rapport";
import dateFormat from "dateformat";


class TestPoudre extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            patient: '',
            DDN: '',
            tel: '',
            lieux: '',
            peau: false,
            vetements: false,
            liveplace: 0,
            otherTests: [],
            errors: [],
            list: [],
        }
        this.nomchange = this.nomchange.bind(this);
    }

    async nomchange(e) {
        this.setState({patient: e.target.value})
        var req = await axios({
            url: '/data/patient/search/' + e.target.value,
            method: 'GET',
        });
        if(req.data.list.length === 1){
            this.setState({
                tel: req.data.list[0].tel,
                DDN: req.data.list[0].naissance,
                lieux: req.data.list[0].living_place
            })

        }
        this.setState({list: req.data.list})

    }

    async componentDidMount() {
        let req = await axios({
            method: 'GET',
            url: '/data/poudre/get',
        })
        this.setState({otherTests: req.data.tests})
    }

    render() {
        let perm = this.context;
        return (
            <div className={"TestPoudre"}>
                <section className={'left'}>
                    <PagesTitle title={'Tests de poudre'}/>
                    <div className={'TestForm'}>
                        <div className={'formtitle'}>
                            <img src={'/assets/images/LONG_EMS_BC_2.png'} alt={''}/>
                            <h1>Test de poudre </h1>

                        </div>
                        <div className={'line'}/>
                        <form onSubmit={async e => {
                            e.preventDefault()
                            await axios({
                                method: 'POST',
                                url: '/data/poudre/add',
                                data: {
                                    patient: this.state.patient,
                                    DDN: this.state.DDN,
                                    tel: this.state.tel,
                                    lieux: this.state.lieux,
                                    peau: this.state.peau,
                                    vetements: this.state.vetements,
                                    liveplace: this.state.liveplace,
                                }
                            }).then(response => {
                                if (response.status === 201){
                                    this.setState({
                                        patient: '',
                                        DDN: '',
                                        tel: '',
                                        lieux: '',
                                        peau: false,
                                        vetements: false,
                                        liveplace: 0,
                                        list: [],
                                    })
                                }
                            })
                        }}>
                            <div className={'PatienId'}>
                                <div className={'pouderFormPart'}>
                                    <label>Patient</label>
                                    <input required type="text" className={(this.state.errors.name ? 'form-error': '')} list={'autocomplete'} autoComplete={'off'} placeholder="prénom nom" value={this.state.name} onChange={this.nomchange}/>
                                    {this.state.list &&
                                    <datalist id={'autocomplete'}>
                                        {this.state.list.map((item)=>
                                            <option>{item.vorname} {item.name}</option>
                                        )}
                                    </datalist>
                                    }
                                    {this.state.errors.patient &&
                                    <ul className={'error-list'}>
                                        {this.props.errors.patient.map((item)=>
                                            <li>{item}</li>
                                        )}
                                    </ul>
                                    }
                                </div>
                                <div className={'pouderFormPart'}>
                                    <label>Téléphone du patient</label>
                                    <input type={'text'} value={this.state.tel} onChange={e => {this.setState({tel:e.target.value})}}/>
                                    {this.state.errors.tel &&
                                    <ul className={'error-list'}>
                                        {this.props.errors.tel.map((item)=>
                                            <li>{item}</li>
                                        )}
                                    </ul>
                                    }
                                </div>
                                <div className={'pouderFormPart'}>
                                    <label>Date de naissance</label>
                                    <input type={'date'} value={this.state.DDN} onChange={e => {this.setState({DDN:e.target.value})}}/>
                                    {this.state.errors.DDN &&
                                        <ul className={'error-list'}>
                                    {this.props.errors.DDN.map((item)=>
                                        <li>{item}</li>
                                        )}
                                        </ul>
                                    }
                                </div>
                                <div className={'pouderFormPart'}>
                                    <label>Lieux de résidence</label>
                                    <select value={this.state.liveplace} onChange={e => {this.setState({liveplace:e.target.value})}}>
                                        <option>LS</option>
                                        <option>BC</option>
                                    </select>
                                </div>
                            </div>
                            <div className={'Testpart'}>
                                <div className={'pouderFormPart linear'}>
                                    <label>Positif sur la peau</label>
                                    <div className={'switch-container'}>
                                        <input id={"switchA"}  className="payed_switch" type="checkbox" checked={this.state.peau} onChange={e => {this.setState({peau:!this.state.peau})}}/>
                                        <label htmlFor={"switchA"} className={"payed_switchLabel"}/>
                                    </div>


                                </div>
                                <div className={'pouderFormPart linear'}>
                                    <label>Positif sur les vêtements</label>
                                    <div className={'switch-container'}>
                                        <input id={"switchB"}  className="payed_switch" type="checkbox" checked={this.state.vetements} onChange={e => {this.setState({vetements:!this.state.vetements})}}/>
                                        <label htmlFor={"switchB"} className={"payed_switchLabel"}/>
                                    </div>

                                </div>
                                <div className={'pouderFormPart '}>
                                    <label>Lieux du prélèvement</label>
                                    <input type={'text'} value={this.state.lieux} onChange={e => {this.setState({lieux:e.target.value})}}/>
                                </div>
                            </div>
                            <button type={'submit'} className={'btn'} >Envoyer</button>
                        </form>
                    </div>
                </section>
                <section className={'right'}>
                    <div className={'ResultListContainer'}>
                        <h1>Histoirque des tests</h1>
                        <div className={'liste'}>
                            <table>
                                <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Vêtement</th>
                                    <th>Peau</th>
                                    <th>Date</th>
                                    <th>PDF</th>
                                </tr>
                                {this.state.otherTests &&
                                    this.state.otherTests.map((test) =>
                                        <tr>
                                            <td>{test.get_patient.vorname} {test.get_patient.name}</td>
                                            <td>{test.on_clothes_positivity ? 'Pos' : 'Neg'}</td>
                                            <td>{test.on_skin_positivity ? 'Pos' : 'Neg'}</td>
                                            <td>{dateFormat(test.created_at, 'yyyy/mm/dd H:MM')}</td>
                                            <td><a className={'btn'} target={'_blank'} href={'/data/poudre/PDF/'+test.id}>Ouvrir</a> </td>
                                        </tr>
                                    )
                                }
                                </thead>
                            </table>
                        </div>
                    </div>
                </section>

            </div>
        )
    }
}
Rapport.contextType = PermsContext;
export default TestPoudre;

