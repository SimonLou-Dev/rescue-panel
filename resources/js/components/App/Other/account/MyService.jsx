import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import UserContext from "../../../context/UserContext";
import {Line, defaults, Chart} from 'react-chartjs-2';
Chart.defaults.plugins.tooltip.enabled = true;
Chart.defaults.backgroundColor = '#FFF';
Chart.defaults.color = '#E9E9E9';
Chart.defaults.font.size = 15;
Chart.defaults.borderColor = '#FFF';
Chart.defaults.elements.point.pointStyle = 'rect';
Chart.defaults.elements.point.radius = 5;
Chart.defaults.elements.line.tension = 0.5;

const Weeklygraph = (props) => {
    let data = null;
    if(props.data !== null){
        data = {
            labels: props.data[0] ,
            datasets: [
                {
                    label: 'Temps de service (h)',
                    data: props.data[1],
                    fill: false,
                    color: '#fff',
                    backgroundColor: ' #FFF',
                    borderColor: ' #ED3444',
                },
            ],
        };
    }else{
        data = null
    }

    const options = {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            x:{
                grid:{
                    drawOnChartArea: false,

                }
            },
            y: {
                beginAtZero: false
            }
        },
    };


    return (
        <div className={'weeklygraph'}>
            <h3>Temps de service par semaine</h3>
            {props.data != null &&
                <Line data={data} options={options}/>
            }

        </div>
    )
}

const Servicegraph = (props) => {

    let nddata = null;
    console.log(typeof props.data)
    if(props.data.graphic !== undefined){
        nddata = {
            labels: props.data.graphic[0],
            datasets: [
                {
                    label: 'durée du service (h)',
                    data: props.data.graphic[1],
                    fill: false,
                    backgroundColor: ' #E9E9E9',
                    borderColor: ' #ED3444',
                },
            ],
        };
    }else{
        nddata = null
    }

    const ndoptions = {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            x: {
                grid: {
                    drawOnChartArea: false,
                }
            },
            y: {
                beginAtZero: false
            }
        }
    };

    return (
        <div className={'ServiceGraph'}>
            <h3>Temps de service cette semaine</h3>
            {props.data.graphic !== undefined &&
                <Line data={nddata} options={ndoptions} />
            }
            <h6>Total : {(props.data.graphic !== undefined  > 0 ? props.data.total : '')}</h6>
            <h6>Ajustement :  {(props.data.graphic !== undefined  > 0 ? props.data.ajustement : '')}</h6>
        </div>
    )
}


function MyService(props) {
    const [modifierAction, setModifierAction] = useState(0);
    const [modifierTime, setModifierTime] = useState('');
    const [modifierReason, setModifierReason] = useState('');
    const [myList, setmyList] = useState([]);
    const [errors, setErrors] = useState([]);
    const user = useContext(UserContext)
    const [weekGraph, setWeekGraph] = useState([]);
    const [serviceGraph, setServiceGraph] = useState([]);

    useEffect(()=>{
        getMyReqList();
        getService();
    }, [])

    const getMyReqList = async () => {
        await axios({
            method: 'GET',
            url: '/data/service/req/mylist'
        }).then(r=>{
            setmyList(r.data.reqs);
        })
    }

    const postReq = async () => {
        await  axios({
            method: 'POST',
            url: '/data/service/req/post',
            data:{
                'reason': modifierReason,
                'montant': modifierAction,
                'time_quantity': modifierTime,
            }
        }).then(r => {
            getMyReqList()
            setModifierAction(0)
            setModifierTime('')
            setModifierReason('')
        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })

    }

    const getService = async () => {
        await axios({
            url: '/data/service/user',
            method: 'GET'
        }).then(req => {
            setWeekGraph(req.data.weekgraph)
            setServiceGraph(req.data.thisWeek)
        })
    }

    return (
        <section className={'PageDisplayed MyService'}>
            <section className={'request'}>
                <section className={'form'}>
                    <div className={'form-part form-inline'}>
                        <label>Service</label>
                        <label>{user.service}</label>
                    </div>
                    <div className={'form-part form-inline'}>
                        <label>actions</label>
                        <select defaultValue={modifierAction} onChange={(e)=>setModifierAction(e.target.value) }>
                            <option value={0} disabled>choisir</option>
                            <option value={1}>ajouter</option>
                            <option value={2}>enlever</option>
                        </select>
                        <ul className={'error-list'}>
                            {errors.action && errors.action.map((item)=>
                                <li>{item}</li>
                            )}
                        </ul>

                    </div>
                    <div className={'form-part form-inline'}>
                        <label>temps </label>
                        <input type={'text'} placeholder={'hh:mm (h heures & m minutes)'} value={modifierTime} className={(errors.time ? 'form-error': '')} onChange={(e)=>{setModifierTime(e.target.value); }}/>
                        <ul className={'error-list'}>
                            {errors.time_quantity && errors.time_quantity.map((item)=>
                                <li>{item}</li>
                            )}
                        </ul>
                    </div>
                    <div className={'form-part form-inline'}>
                        <label>raison </label>
                        <input type={'text'} maxLength={25} value={modifierReason} className={(errors.tel ? 'form-error': '')} onChange={(e)=>{setModifierReason(e.target.value); }}/>
                        <ul className={'error-list'}>
                            {errors.reason && errors.reason.map((item)=>
                                <li>{item}</li>
                            )}
                        </ul>
                    </div>
                    <div className={'form-part form-inline'}>
                        <button className={'btn'} onClick={postReq}>envoyer</button>
                    </div>
                </section>
                <section className={'list'}>
                    <table>
                        <thead>
                            <tr>
                                <th>semaine</th>
                                <th>état</th>
                                <th>durée</th>
                                <th>raison</th>
                            </tr>
                        </thead>
                        <tbody>
                        {myList && myList.map((l)=>
                            <tr key={l.id}>
                                <td>{l.week_number}</td>
                                <td>{l.accepted === null ? 'en cours' : (l.accepted ? 'accepté' : 'refusée')}</td>
                                <td>{l.time_quantity}</td>
                                <td>{l.reason}</td>
                            </tr>
                        )}
                        </tbody>
                    </table>
                </section>
            </section>
            <section className={'review'}>
                <section className={'graphic'}>
                    <Weeklygraph data={weekGraph}/>
                    <Servicegraph data={serviceGraph}/>
                </section>

            </section>
        </section>
    )
}

export default MyService;
