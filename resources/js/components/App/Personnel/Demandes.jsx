import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import {Link} from "react-router-dom";
import UserContext from "../../context/UserContext";

function Demandes(props) {
    const [serviceList, setServiceList] = useState([])
    const [primeList, setPrimeList] = useState([])
    const [absenceList, setAbsenceList] = useState([])
    const [remboursementList, setRemboursementList] =  useState([]);
    const user = useContext(UserContext)

    useEffect(()=>{
        getService()
        getPrime()
        getAbs()
        getRemboursements()
    },[])

    const getService = async () => {
        await axios({
            method: 'GET',
            url: '/data/service/req/waitinglist',
        }).then(r=>{
            setServiceList(r.data.list)
        })

    }

    const getRemboursements = async () => {
        await axios({
            method: 'GET',
            url: '/data/remboursements/all',
        }).then(r=>{
            setRemboursementList(r.data.remboursements)
        })

    }

    const getPrime = async () => {
        await axios({
            method: 'GET',
            url: '/data/primes/getall',
        }).then(r=>{
            setPrimeList(r.data.primes)
        })
    }

    const getAbs = async () => {
        await axios({
            method: 'GET',
            url: '/data/admin/absence',
        }).then(r=>{
            setAbsenceList(r.data.abs)
        })
        //t
    }



    return (<div className={'demandes'}>
        <section className={'header'}>
            {(user.grade.admin || user.grade.viewAll_service_req) &&
                <a href={'#service'} className={'btn'}>service</a>
            }
            {(user.grade.admin || user.grade.viewAll_prime_req) &&
                <a href={'#primes'} className={'btn'}>primes</a>
            }
            {(user.grade.admin || user.viewAll_absences_req) &&
                <a href={'#abs'} className={'btn'}>absences</a>
            }
            <a href={'#rmb'} className={'btn'}>remboursements</a>


        </section>
        <section className={'page-container'}>
            {(user.grade.admin || user.grade.viewAll_service_req) &&
                <section className={'request-container'}>
                    <h1 id={"service"}>Service</h1>
                    <section className={'table-container'}>
                        <table>
                            <thead>
                            <tr>
                                <th>personnel</th>
                                <th>semaine</th>
                                <th>temps</th>
                                <th>raison</th>
                                <th>date</th>
                                <th>état</th>
                                <th>responsable</th>
                                <th>action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {serviceList && serviceList.map((s)=>
                                <tr key={s.id}>
                                    <td className={'clickable'}><Link to={'/personnel/fiche/'+s.get_user.id}>{s.get_user.name}</Link></td>
                                    <td>{s.week_number}</td>
                                    <td>{s.adder ? '+':'-'}{s.time_quantity}</td>
                                    <td>{s.reason}</td>
                                    <td>{s.created_at}</td>
                                    <td>{s.accepted === null ? 'en cours' : (s.accepted ? 'accepté' : 'refusée')}</td>
                                    <td>{s.accepted !== null ? s.get_admin.name : ''}</td>
                                    <td className={'btn-container'}>{s.accepted === null &&
                                        <button className={'btn'} disabled={!(user.grade.admin || user.grade.modify_service_req)} onClick={async () => {
                                            await axios({
                                                method: 'PUT',
                                                url: '/data/service/req/accept/' + s.id
                                            }).then(() => getService())
                                        }}><img src={'/assets/images/accept.png'} alt={''}/> </button>
                                    }
                                        {s.accepted === null &&
                                            <button className={'btn'} disabled={!(user.grade.admin || user.grade.modify_service_req)} onClick={async () => {
                                                await axios({
                                                    method: 'PUT',
                                                    url: '/data/service/req/refuse/' + s.id
                                                }).then(() => getService())
                                            }}><img src={'/assets/images/decline.png'} alt={''}/></button>
                                        }
                                    </td>
                                </tr>
                            )}
                            </tbody>
                        </table>
                    </section>
                </section>
            }
            {(user.grade.admin || user.grade.viewAll_prime_req) &&
                <section className={'request-container'}>
                    <h1 id={'primes'}> Primes</h1>
                    <section className={'table-container'}>
                        <table>
                            <thead>
                            <tr>
                                <th>personnel</th>
                                <th>semaine</th>
                                <th>date</th>
                                <th>montant</th>
                                <th>raison</th>
                                <th>état</th>
                                <th>responsable</th>
                                <th>action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {primeList && primeList.map((s)=>
                                <tr key={s.id}>
                                    <td className={'clickable'}><Link to={'/personnel/fiche/'+s.get_user.id}>{s.get_user.name}</Link></td>
                                    <td>{s.week_number}</td>
                                    <td>{s.created_at}</td>
                                    <td>${s.montant}</td>
                                    <td>{s.reason}</td>
                                    <td>{s.accepted === null ? 'en cours' : (s.accepted ? 'accepté' : 'refusée')}</td>
                                    <td>{s.accepted !== null ? (s.admin_id === 0 ? 'MDT' : s.get_admin.name) : ''}</td>
                                    <td className={'btn-container'}>{s.accepted === null &&
                                        <button className={'btn'} disabled={!(user.grade.admin || user.grade.modify_prime_req)}  onClick={async () => {
                                            await axios({
                                                method: 'PUT',
                                                url: '/data/primes/accept/' + s.id
                                            }).then(() => getPrime())
                                        }}><img src={'/assets/images/accept.png'} alt={''}/> </button>
                                    }
                                        {s.accepted === null &&
                                            <button className={'btn'}  disabled={!(user.grade.admin || user.grade.modify_prime_req)} onClick={async () => {
                                                await axios({
                                                    method: 'PUT',
                                                    url: '/data/primes/refuse/' + s.id
                                                }).then(() => getPrime())
                                            }}><img src={'/assets/images/decline.png'} alt={''}/></button>
                                        }
                                    </td>

                                </tr>
                            )}
                            </tbody>
                        </table>
                    </section>
                </section>
            }
            {(user.grade.admin || user.grade.viewAll_absences_req) &&
                <section className={'request-container'}>
                    <h1 id={'abs'}>Absences</h1>
                    <section className={'table-container'}>
                        <table>
                            <thead>
                            <tr>
                                <th>personnel</th>
                                <th>date de demande</th>
                                <th>raison</th>
                                <th>début</th>
                                <th>fin</th>
                                <th>état</th>
                                <th>responsable</th>
                                <th>action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {absenceList && absenceList.map((s)=>
                                <tr key={s.id}>
                                    <td className={'clickable'}><Link to={'/personnel/fiche/'+s.get_user.id}>{s.get_user.name}</Link></td>
                                    <td>{s.created_at}</td>
                                    <td>{s.reason}</td>
                                    <td>{s.start_at}</td>
                                    <td>{s.end_at}</td>
                                    <td>{s.accepted === null ? 'en cours' : (s.accepted ? 'accepté' : 'refusée')}</td>
                                    <td>{s.accepted !== null ? s.get_admin.name : ''}</td>
                                    <td className={'btn-container'}>{s.accepted === null &&
                                        <button className={'btn'} disabled={!(user.grade.admin || user.grade.modify_absences_req)} onClick={async () => {
                                            await axios({
                                                method: 'PUT',
                                                url: '/data/absence/accept/' + s.id
                                            }).then(() => getAbs())
                                        }}><img src={'/assets/images/accept.png'} alt={''}/> </button>
                                    }
                                        {s.accepted === null &&
                                            <button className={'btn'} disabled={!(user.grade.admin || user.grade.modify_absences_req)}  onClick={async () => {
                                                await axios({
                                                    method: 'PUT',
                                                    url: '/data/absence/refuse/' + s.id
                                                }).then(() => getAbs())
                                            }}><img src={'/assets/images/decline.png'} alt={''}/></button>
                                        }
                                    </td>
                                </tr>
                            )}
                            </tbody>
                        </table>
                    </section>

                </section>
            }

            <section className={'request-container'}>
                <h1 id={'rmb'}>Remboursements</h1>
                <section className={'table-container'}>
                    <table>
                        <thead>
                        <tr>
                            <th>personnel</th>
                            <th>date de demande</th>
                            <th>raison</th>
                            <th>montant</th>
                            <th>état</th>
                            <th>responsable</th>
                            <th>action</th>
                        </tr>
                        </thead>
                        <tbody>
                        {remboursementList && remboursementList.map((s)=>
                            <tr key={s.id}>
                                <td className={'clickable'}><Link to={'/personnel/fiche/'+s.get_user.id}>{s.get_user.name}</Link></td>
                                <td>{s.created_at}</td>
                                <td>{s.reason}</td>
                                <td>{s.montant}</td>
                                <td>{s.accepted === null ? 'en cours' : (s.accepted ? 'accepté' : 'refusée')}</td>
                                <td>{s.accepted !== null ? s.get_admin.name : ''}</td>
                                <td className={'btn-container'}>{s.accepted === null &&
                                    <button className={'btn'} onClick={async () => {
                                        await axios({
                                            method: 'PATCH',
                                            url: '/data/remboursements/accept/' + s.id
                                        }).then(() => getRemboursements())
                                    }}><img src={'/assets/images/accept.png'} alt={''}/> </button>
                                }
                                    {s.accepted === null &&
                                        <button className={'btn'} onClick={async () => {
                                            await axios({
                                                method: 'PATCH',
                                                url: '/data/remboursements/refuse/' + s.id
                                            }).then(() => getRemboursements())
                                        }}><img src={'/assets/images/decline.png'} alt={''}/></button>
                                    }
                                </td>
                            </tr>
                        )}
                        </tbody>
                    </table>
                </section>

            </section>
        </section>

    </div> )
}

export default Demandes;
