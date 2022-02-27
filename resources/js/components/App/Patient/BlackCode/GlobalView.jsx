import React, {useContext, useEffect, useState} from 'react';
import CardComponent from "../../../props/CardComponent";
import PageNavigator from "../../../props/PageNavigator";
import Searcher from "../../../props/Searcher";
import axios from "axios";
import searcher from "../../../props/Searcher";
import UserContext from "../../../context/UserContext";

function GlobalView(props) {
    const [types, setTypes] = useState([]);
    const [endeds, setEndeds] = useState([]);
    const [runnings, setRunnings] = useState([]);
    const [page, setPage] = useState(1);
    const [pagination, setpagination] = useState([]);
    const [search, setSearche] = useState("");
    const [popupOpened, setpopupOpening] = useState(false);
    const [type, setType] = useState(0);
    const [place, setPlace] = useState('');
    const [errors, setErrors] = useState([]);
    const user = useContext(UserContext);



    useEffect(()=>{
        UpdateBC();
        let GlobalChannel = window.GlobalChannel;
        GlobalChannel.bind('BlackCodeListEdited',() => {
            UpdateBC();
        });

        return () => {
            GlobalChannel.unbind('BlackCodeListEdited');
        }
    }, []);

    const UpdateBC = async (v = search, c = page) => {
        if(c !== page){
            setPage(c)
        }
        if(v !== search){
            setSearche(v)
        }

        await axios({
            method: 'GET',
            url: "/data/blackcode/load?query=" + v + "&page=" + c,
        }).then(response => {
            let data = response.data;
            if(data.userBC !== null){
                Redirection('/blackcodes/'+ data.userBC.service +'/' + data.userBC.bc_id);
            }
            let final = [];
            let keys = Object.keys(data.ended.data);
            keys.forEach((key) => {
                final[key] = data.ended.data[key];
            });
            data.ended.data = final;
            setEndeds(data.ended.data);
            setpagination(data.ended)
            setRunnings(data.active);
            setTypes(data.types);
        })
    }

    const Redirection = (url) => {
        props.history.push(url)
    }

    const OpenBc = async () => {
        await axios({
            method: 'POST',
            url: '/data/blackcode/create',
            data: {
                'place': place,
                'type': type,
            }
        }).then(r => {
            if(r.status === 201){
                Redirection('/blackcodes/'+r.endUrl);
            }
        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })
    }

    return (<div className={'BC-GlobalView'}>
        <section className={'new ' + (popupOpened ? 'popupBg':'')}>
            <CardComponent title={'en cours'}>
                <div className={'header'}>
                    <button className={'btn'} onClick={()=>{setpopupOpening(!popupOpened)}} disabled={!(user.grade.admin || user.BC_open)}>ajouter</button>
                </div>
                <div className={'BCtable'}>
                    {runnings && runnings.map((running) =>
                        <div className={'table-item'} key={running.id} onClick={()=>{Redirection(
                           '/blackcodes/' +  (running.service === 'LSCoFD' ? 'fire' : 'medic') + '/' + running.id
                        )}}>
                            <img src={'/assets/images/' + running.service + '.png'} alt={''}/><h5>{running.get_type.name} - {running.place}</h5>
                        </div>
                    )}

                </div>
            </CardComponent>
        </section>
        <section className={'alt ' + (popupOpened ? 'popupBg':'')}>
            <CardComponent title={'terminé(s)'}>
                <div className={'header'}>
                    <Searcher value={search} callback={(v) => {UpdateBC(v)}}/>
                    <button className={'btn'}><img alt={''} src={'/assets/images/xls.png'}/></button>
                    <PageNavigator prev={()=> {UpdateBC(search, page-1)}} next={()=> {UpdateBC(search, page+1)}} prevDisabled={(pagination.prev_page_url === null)} nextDisabled={(pagination.next_page_url === null)}/>
                </div>
                <div className={'BCtable'}>
                    {endeds && endeds.map((ended) =>
                        <div className={'table-item'} key={ended.id} onClick={()=>{Redirection(
                            '/blackcodes/' +  (ended.service === 'LSCoFD' ? 'fire' : 'medic') + '/' + ended.id
                        )}}>
                            <img src={'/assets/images/' + ended.service + '.png'} alt={''}/><h5>{ended.get_type.name} - {ended.place}</h5>
                        </div>
                    )}
                </div>
            </CardComponent>

        </section>
        {popupOpened &&
            <section className={'popup'}>
                <CardComponent  title={'Ouvrir un BC / Incendie'}>

                    <div className={'form-item form-column'}>
                        <label>Lieux</label>
                        <input type={'text'} className={'form-input'} value={place} onChange={(e)=>{setPlace(e.target.value)}}/>
                        {errors.place &&
                            <div className={'errors-list'}>
                                <ul>
                                    {errors.lieux.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>

                    <div className={'form-item form-column'}>
                        <label>Type</label>
                        <select value={type} onChange={(e)=>{setType(e.target.value)}}>
                            <option key={0} value={0} disabled={true}>choisir</option>
                            {types && types.map((t)=>
                                <option key={t.id} value={t.id}>{t.name}</option>
                            )}
                        </select>
                    </div>

                    <div className={'form-item form-line'}>
                        <button className={'btn --medium'} onClick={OpenBc}> valider </button>
                        <button className={'btn --medium'} onClick={()=>{setpopupOpening(!popupOpened)}}> fermer </button>

                    </div>

                </CardComponent>
            </section>
        }
    </div> )
}

export default GlobalView;
