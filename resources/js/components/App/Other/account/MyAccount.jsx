import React, {useState} from 'react';
import MyInfo from "./MyInfo";
import MyService from "./MyService";
import MyPrimes from "./MyPrimes";
import MyRemboursements from "./MyRemboursements";
import MyAbs from "./MyAbs";

function MyAccount(props) {
    const [infos, setInfos] = useState(true);
    const [Service, setService] = useState(false);
    const [Primes, setPrimes] = useState(false);
    const [Remboursement, setRemboursement] = useState(false);
    const [absences, setAbsences] = useState(false);

    return (<div className={'MyAccount'}>
        <div className={'center'}>
            <section className={'pageSelector'}>
                <button className={(infos ? 'selected' : '')} onClick={()=>{
                    setInfos(true)
                    setService(false)
                    setPrimes(false)
                    setRemboursement(false)
                    setAbsences(false)
                }}> Mes infos</button>
                <button className={(Service ? 'selected' : '')}onClick={()=>{
                    setInfos(false)
                    setService(true)
                    setPrimes(false)
                    setRemboursement(false)
                    setAbsences(false)
                }}>Service</button>
                <button className={(Primes ? 'selected' : '')} onClick={()=>{
                    setInfos(false)
                    setService(false)
                    setPrimes(true)
                    setRemboursement(false)
                    setAbsences(false)
                }}>Primes</button>
                <button className={(Remboursement ? 'selected' : '')} onClick={()=>{
                    setInfos(false)
                    setService(false)
                    setPrimes(false)
                    setRemboursement(true)
                    setAbsences(false)
                }}>Remboursement</button>
                <button className={(absences ? 'selected' : '')} onClick={()=>{
                    setInfos(false)
                    setService(false)
                    setPrimes(false)
                    setRemboursement(false)
                    setAbsences(true)
                }}>Absences</button>
            </section>
            {infos &&
                <MyInfo history={props.history}/>
            }
            {Service &&
                <MyService history={props.history}/>
            }
            {Primes &&
                <MyPrimes history={props.history}/>
            }
            {Remboursement &&
                <MyRemboursements history={props.history}/>
            }
            {absences &&
                <MyAbs history={props.history}/>
            }

        </div>

    </div> )
}

export default MyAccount;
