import React, {useEffect, useState} from 'react';
import axios from "axios";
import Searcher from "../../../props/Searcher";
import PageNavigator from "../../../props/PageNavigator";
import CardComponent from "../../../props/CardComponent";
/*

  faireRedirection() {
    let url = "maNouvelleURL",
    this.props.history.push(url),
  }

 */



function DossiersPatient(props) {
    const [patients, setPatients] = useState();
    const [search, setSearch] = useState();
    const [selected, setSelectede] = useState();
    const [name, setName] = useState();
    const [ddn , setDDn] = useState();
    const [tel, setTel] = useState();
    const [liveplace, setLiveplace] = useState();
    const [impaye, setimpaye] = useState();
    const [bloodgroup, setbloodgroup] = useState();

    useEffect(()=> {

    }, [])

    const setSelected = (a) => {
        setSelected(a);
        //TODO : request qui vérifie les impaye
    }

    const searcher = async () => {
        await axios({
            method: 'GET',
            url: '/data/patient/getAll/',
            data: {
                search: search,
            }
        }).then(response => {
            setPatients(response.data.patients);
        })
    }

    return (<div className={"dossiers"}>
        <section className={'table'}>
            <div className={'table-header'}>
                <Searcher value={search} callback={(v) => {setSearch(v)}}/>
                <PageNavigator/>
            </div>
            <div className={'table-content'}>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>prénom nom</th>
                            <th>téléphone</th>
                            <th>date de naissance</th>
                            <th>groupe sanguin</th>
                            <th/>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Jean claude</td>
                            <td>5557894</td>
                            <td>32/02/2000</td>
                            <td>AB+</td>
                            <td><button className={'btn'}><img src={'/assets/images/edit.png'} alt={''}/> </button> </td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Jean claude</td>
                            <td>5557894</td>
                            <td>32/02/2000</td>
                            <td>AB+</td>
                            <td><button className={'btn'}><img src={'/assets/images/edit.png'} alt={''}/> </button> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section className={'patient-form'}>
            <CardComponent title={'Informations'}>
                <div className={'form-item form-column'}>
                    <label>prénom nom</label>
                    <input type={'text'} className={'form-input'} list={'autocomplete'} value={name} onChange={(e)=>{setName(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
                <div className={'form-item form-column'}>
                    <label>date de naissance</label>
                    <input type={'date'} className={'form-input'} value={ddn} onChange={(e)=>{setDDn(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
                <div className={'form-item form-column'}>
                    <label>téléphone</label>
                    <input type={'text'} className={'form-input'} value={tel} onChange={(e)=>{setTel(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
                <div className={'form-item form-column'}>
                    <label>Lieux de vie</label>
                    <input type={'text'} className={'form-input'} value={liveplace} onChange={(e)=>{setLiveplace(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
                <div className={'form-item form-column'}>
                    <label>Groupe saunguin</label>
                    <input type={'text'} className={'form-input'} value={bloodgroup} onChange={(e)=>{setbloodgroup(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>

                <div className={'form-item form-column'}>
                    <button className={'btn'}>valider</button>
                </div>
            </CardComponent>
        </section>




    </div> )
}

export default DossiersPatient;
