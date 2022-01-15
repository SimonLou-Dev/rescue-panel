import React, {useState} from 'react';
import CardComponent from "../../../props/CardComponent";
import SwitchBtn from "../../../props/SwitchBtn";

function RapportReview(props) {
    const [interdate, setinterdate] = useState();
    const [interhour, setinterhour]= useState();
    const [lieux, setlieux] = useState();
    const [intertype, setintertypes] = useState();
    const [transport, settransport] = useState();
    const [ata, setata] = useState();
    const [montant, setmontant] = useState();
    const [payed, setpayed] = useState(false);
    const [desc, setdesc] = useState();
    const [impaye, setimpaye] = useState();
    const [bloodgroup, setbloodgroup] = useState();

    const [transportlist, settransportlist] = useState();
    const [intertypeslist ,setintertypeslist] = useState();

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={"RapportReview"}>
        <section className={'interList'}>
            <CardComponent title={'Interventions'}>
                <div className={'intervention-table'}>
                    <div className={'inter-item'}>
                        <h5>SAMS 15-01-2020</h5>
                    </div>
                    <div className={'inter-item'}>
                        <h5>SAMS 15-01-2020</h5>
                    </div>
                    <div className={'inter-item'}>
                        <h5>LSCoFD 15-01-2020</h5>
                    </div>
                    <div className={'inter-item'}>
                        <h5>SAMS 15-01-2020</h5>
                    </div>

                </div>
                <div className={'navigation'}>
                    <button className={'btn'} onClick={()=>{Redirection('/patients/rapport?patientId=1')}}>nouveau</button>
                    <button className={'btn'} onClick={()=>{Redirection('/patients/dossiers')}}>retour</button>
                </div>

            </CardComponent>
        </section>
        <section className={'intervention'}>
            <CardComponent title={'Intervention'}>
                <div className={'form-item form-column'}>
                    <label>Date et heure</label>
                    <input type={'date'} className={'form-input'} value={interdate} onChange={(e)=>{setinterdate(e.target.value)}}/>
                    <input type={'time'} className={'form-input'} value={interhour} onChange={(e)=>{setinterhour(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
                <div className={'form-item form-column'}>
                    <label>Lieux</label>
                    <input type={'text'} className={'form-input'} value={lieux} onChange={(e)=>{setlieux(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
                <div className={'form-item form-column'}>
                    <label>Type d'intervention</label>
                    <select value={intertype} onChange={(e)=>{setintertypes(e.target.value)}}>
                        {intertypeslist && intertypeslist.map((broum)=>
                            <option key={broum.id} value={broum.id}>{broum.name}</option>
                        )}
                    </select>
                </div>
            </CardComponent>
            <CardComponent title={'ATA'}>
                <div className={'form-item form-column'}>
                    <label>ATA</label>
                    <input type={'text'} className={'form-input'} value={ata} onChange={(e)=>{setata(e.target.value)}}/>
                    <label className={'form-healper'}>ex: 13h 3m</label>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
            </CardComponent>
            <CardComponent title={'Facturation'}>
                <div className={'form-item form-column'}>
                    <label>Montant (en $)</label>
                    <input type={'number'} className={'form-input'} value={montant} onChange={(e)=>{setmontant(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
                <div className={'form-item form-line'}>
                    <label>Payé</label>
                    <SwitchBtn number={"a0"} checked={payed} callback={(e)=>{setpayed(!payed)}}/>
                </div>
            </CardComponent>
            <CardComponent title={'Transport'}>
                <div className={'form-item form-column'}>
                    <label>Transport</label>
                    <select value={transport} onChange={(e)=>{settransport(e.target.value)}}>
                        {transportlist && transportlist.map((broum)=>
                            <option key={broum.id} value={broum.id}> transport : {broum.name}</option>
                        )}
                    </select>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
            </CardComponent>
            <CardComponent title={'Description'}>
                <div className={'form-item form-column'}>
                    <textarea className={'form-input'} rows={10} value={desc} onChange={(e)=>{setdesc(e.target.value)}}/>
                    <div className={'errors-list'}>
                        <ul>
                            <li>test</li>
                        </ul>
                    </div>
                </div>
            </CardComponent>
        </section>





    </div> )
}

export default RapportReview;
