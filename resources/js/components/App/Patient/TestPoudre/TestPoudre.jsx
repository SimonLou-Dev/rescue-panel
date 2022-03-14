import React, {useContext, useEffect, useState} from 'react';
import CardComponent from "../../../props/CardComponent";
import axios from "axios";
import SwitchBtn from "../../../props/SwitchBtn";
import Searcher from "../../../props/Searcher";
import PageNavigator from "../../../props/PageNavigator";
import UserContext from "../../../context/UserContext";

function TestPoudre(props) {
    const [name, setName] = useState();
    const [ddn , setDDn] = useState();
    const [tel, setTel] = useState();
    const [liveplace, setLiveplace] = useState();
    const [prevPlace, setPrevPlace] = useState();
    const [searching, setsearching] = useState();
    const [skinPresence, setSkinPresence] = useState(false);
    const [clothPresence, setClothPresence] = useState(false);
    const [search, setSearch] = useState();
    const [errors, setErrors] = useState([]);
    const [page, setPage] = useState(0);
    const [paginate, setPaginate] = useState([]);
    const [tests, setTest] = useState([]);
    const user = useContext(UserContext);

    const searchPatient = async (search) => {
        if(search.length > 0){
            await axios({
                method: 'GET',
                url: '/data/patient/search/'+search,
            }).then((response)=>{
                setsearching(response.data.patients);
                if (response.data.patients.length === 1 && response.data.patients[0].name === search) {
                    let patient = response.data.patients[0];
                    setName(patient.name);
                    setDDn(patient.naissance);
                    setTel(patient.tel);
                    setLiveplace(patient.living_place);
                }
            })
        }
    }

    const patientList = async (searche = search, newpage = page) => {
        if(newpage !== page){
            setPage(newpage);
        }

        if(searche !== search){
            setSearch(searche);
            setPage(1)
            newpage = 1
        }
        await axios({
            url : '/data/poudre/get?query='+searche+'&page='+newpage,
            method: 'GET'
        }).then(r => {
            setTest(r.data.tests.data)
            setPaginate(r.data.tests)
        })

    }

    useEffect(()=>{
        patientList('')
    }, [])

    const postForm = async () => {
        await  axios({
            url:'/data/poudre/add',
            method: 'POST',
            data: {
                name: name,
                ddn: ddn,
                tel: tel,
                liveplace: liveplace,
                skinPresence: skinPresence,
                clothPresence: clothPresence,
                place: prevPlace,
            }
        }).then((r)=> {
            if(r.status === 201){
                setName('')
                setTel('')
                setDDn('')
                setLiveplace('')
                setSkinPresence(false)
                setClothPresence(false)
                setPrevPlace('')
                searchPatient('')
                patientList();
            }
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })
    }

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'testPoudre'}>
        <section className={'makeTest'}>
            <CardComponent title={'test de poudre'}>
                <section className={'test-component'}>
                    <section className={'patientInfos'}>
                        <div className={'form-item form-column'}>
                            <label>prénom nom</label>
                            <input type={'text'} className={'form-input'} list={'autocomplete'} value={name} onChange={(e)=>{setName(e.target.value), searchPatient(e.target.value)}}/>
                            {searching &&
                                <datalist id={'autocomplete'} >
                                    {searching.map((item)=>
                                        <option key={item.id}>{item.name}</option>
                                    )}
                                </datalist>
                            }
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
                            <input type={'date'} className={'form-input'} value={ddn} onChange={(e)=>{setDDn(e.target.value)}}/>
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


                    </section>
                    <section className={'poudre'}>
                        <div className={'form-item form-column'}>
                            <label>Lieux de prélèvement</label>
                            <input type={'text'} className={'form-input'} value={prevPlace} onChange={(e)=>{setPrevPlace(e.target.value)}}/>
                            {errors.place &&
                                <div className={'errors-list'}>
                                    <ul>
                                        {errors.place.map((item)=>
                                                <li>test</li>
                                        )}
                                    </ul>
                                </div>
                            }
                        </div>
                        <h2>Présence de poudre</h2>
                        <div className={'poudre-presence'}>
                            <label>Sur la peau</label>
                            <SwitchBtn number={'A0'} checked={skinPresence} callback={()=>{setSkinPresence(!skinPresence)}}/>
                        </div>
                        <div className={'poudre-presence'}>
                            <label>Sur les vètements</label>
                            <SwitchBtn number={'A1'} checked={clothPresence} callback={()=>{setClothPresence(!clothPresence)}}/>
                        </div>
                    </section>
                </section>
                <section className={'footer'}>
                    <button className={'btn'} onClick={postForm} disabled={!(user.grade.admin || user.grade.poudretest_create)}>valider</button>
                </section>
            </CardComponent>

        </section>
        <section className={'test-table'}>
            <div className={'table-header'}>
                <Searcher value={search} callback={(v) => {patientList(v)}}/>
                <PageNavigator prev={()=> {patientList(search, page-1)}} next={()=> {patientList(search, page+1)}} prevDisabled={(paginate.prev_page_url === null)} nextDisabled={(paginate.next_page_url === null)}/>
            </div>
            <div className={'table-content'}>
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>prénom nom</th>
                        <th>peau</th>
                        <th>vêtements</th>
                        <th>pdf</th>
                        <th>date</th>
                    </tr>
                    </thead>
                    <tbody>
                    {tests && tests.map((test)=>
                        <tr key={test.id}>
                            <td>{test.id}</td>
                            <td className={'clickable'} onClick={()=>{Redirection('/patients/'+test.get_patient.id+'/view')}}>{test.get_patient.name}</td>
                            <td><img src={(test.on_skin_positivity ? '/assets/images/accept.png' : '/assets/images/decline.png')} alt={''}/></td>
                            <td><img src={(test.on_clothes_positivity ? '/assets/images/accept.png' : '/assets/images/decline.png')} alt={''}/></td>
                            <td><a href={'/data/poudre/PDF/'+test.id} target={"_blank"} className={'btn'}><img src={'/assets/images/pdf.png'} alt={''}/></a> </td>
                            <td>{test.created_at}</td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </section>
    </div>)
}

export default TestPoudre;
