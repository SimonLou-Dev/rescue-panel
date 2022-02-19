import React, {useEffect, useState} from 'react';
import axios from "axios";
import Searcher from "../../../props/Searcher";
import PageNavigator from "../../../props/PageNavigator";
import CardComponent from "../../../props/CardComponent";
import dateFormat from "dateformat";

function DossiersPatient(props) {
    const [patients, setPatients] = useState([]);
    const [pagination, setPagination] = useState([]);
    const [search, setSearch] = useState();
    const [selected, setSelectede] = useState();
    const [name, setName] = useState();
    const [ddn , setDDn] = useState();
    const [tel, setTel] = useState();
    const [liveplace, setLiveplace] = useState();
    const [bloodgroup, setbloodgroup] = useState();
    const [page, setPage] = useState(1);
    const [impaye, setImpaye] = useState(null);
    const [impayePhrase, setImpayePhrase] = useState(null);
    const [errors, setErrors]= useState([])

    useEffect(()=> {
        searcher('');

    }, [])

    const setSelected = async (a) => {
        setSelectede(a);
        await axios({
            method: 'GET',
            url: '/data/patient/get/'+a
        }).then((r) => {
            let patient = r.data.patient;
            setName(patient.name);
            setDDn(patient.naissance);
            setTel(patient.tel);
            setLiveplace(patient.living_place);
            setbloodgroup(patient.blood_group);
            if (r.data.number === 0) {
                setImpaye(false);
                setImpayePhrase('aucun impayé auprès de l\'OMC ');
            } else {
                setImpaye(true);
                setImpayePhrase(r.data.number + " factures à payer ($" + r.data.montant + ")");
            }
        })
    }

    const Redirection = (url) => {
       props.history.push(url)
    }

    const searcher = async (v) => {
        setSearch(v);
        await axios({
            method: 'GET',
            url: '/data/patient/getAll?query='+v+"&page="+page,
        }).then(response => {
            setPatients(response.data.patients.data);
            setPagination(response.data.patients);
        })
    }

    const PostUpdate = async () => {
        await axios({
            method: 'PUT',
            url: '/data/patient/update/'+selected,
            data:{
                name: name,
                tel: tel,
                ddn: ddn,
                liveplace: liveplace,
                bloodgroup: bloodgroup,
            }
        }).then((response)=>{
            if(response.status === 201){
                setName('');
                setTel('')
                setLiveplace('')
                setDDn('');
                setbloodgroup('')
                searcher(search);
                setSelectede()
                setErrors([])
                setImpayePhrase(null)
                setImpaye(null)
            }
        })
    }

    return (<div className={"dossiers"}>
        <section className={'table'}>
            <div className={'table-header'}>
                <Searcher value={search} callback={(v) => {searcher(v)}}/>
                <PageNavigator prev={()=> {setPage(page-1)}} next={()=> {setPage(page+1)}} prevDisabled={(pagination.prev_page_url === null)} nextDisabled={(pagination.next_page_url === null)}/>
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
                        {patients && patients.map((patient)=>
                            <tr key={patient.id} >
                                <td>{patient.id}</td>
                                <td className={'clickable'} style={{color: patient.colorOfName}} onClick={()=>{Redirection('/patients/'+ patient.id +'/view')}}>{patient.name}</td>
                                <td>{patient.tel}</td>
                                <td>{dateFormat(patient.naissance, 'dd/mm/yyyy')}</td>
                                <td>{patient.blood_group}</td>
                                <td><button className={'btn'} onClick={()=>{setSelected(patient.id)}} ><img src={'/assets/images/edit.png'} alt={''}/> </button> </td>
                            </tr>
                        )}

                    </tbody>
                </table>
            </div>
        </section>
        <section className={'patient-form'}>
            <CardComponent title={'Informations'}>
                <div className={'form-item form-column'}>
                    <label>prénom nom</label>
                    <input type={'text'} className={'form-input'} value={name} onChange={(e)=>{setName(e.target.value), searchPatient(e.target.value)}}/>
                    {errors.name &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.name.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }

                </div>
                <div className={'form-item form-column'}>
                    <label>date de naissance</label>
                    <input type={'date'} pattern={"[0-9]{2}/[0-9]{2}/[0-9]{4}"} className={'form-input'} value={ddn} onChange={(e)=>{setDDn(e.target.value)}}/>
                    {errors.ddn &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.ddn.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
                <div className={'form-item form-column'}>
                    <label>téléphone</label>
                    <input type={'text'} className={'form-input'} value={tel} onChange={(e)=>{setTel(e.target.value)}}/>
                    {errors.tel &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.tel.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
                <div className={'form-item form-column'}>
                    <label>Lieux de vie</label>
                    <input type={'text'} className={'form-input'} value={liveplace} onChange={(e)=>{setLiveplace(e.target.value)}}/>
                    {errors.liveplace &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.liveplace.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
                <div className={'form-item form-column'}>
                    <label>Groupe saunguin</label>
                    <input type={'text'} className={'form-input'} value={bloodgroup} onChange={(e)=>{setbloodgroup(e.target.value)}}/>
                    {errors.bloodgroup &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.bloodgroup.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
                {impaye != null &&
                    <h5 className={!impaye ? 'cool' : 'pascool'}>{impayePhrase}</h5>
                }
                <button className={'btn'} onClick={PostUpdate}>Valider</button>
            </CardComponent>
        </section>




    </div> )
}

export default DossiersPatient;
