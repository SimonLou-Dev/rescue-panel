import React, {useEffect, useState} from "react";
import axios from 'axios';


function Service(props){
    const [timer, setTimer] = useState(120)
    const [service, setService] = useState(false);
    const [TimerID, setTimerID] = useState(null);

    //load Component
    useEffect( async()=>{
        let req = await axios({
            method: "GET",
            url: '/data/getstatus',
        });
        setService()
        props.serviceUpade(req.data.service === 1)
    }, [])

    const TimerFunction = () => {
        const id = setInterval(()=>{
            setTimer((prev )=>{
                if(prev === 0){
                    clearInterval(TimerID);
                    return prev
                }else{
                    return prev - 1;
                }
            })
        },1000)
        setTimerID(id)
    }

    const onserviceClicked = () => {
        setTimer(120)
        setService(false);
        TimerFunction();
        dbRequest().then(()=>{
            props.serviceUpade(false)
        })
    }

    const offserviceClicked = () => {
        setTimer(120)
        setService(true);
        TimerFunction();
        dbRequest().then(()=>{
            props.serviceUpade(true)
        })

    }

    const dbRequest = async () => {
        await axios({
            method: "PUT",
            url: '/data/setstatus',
        });
    }


    if(timer !== 0 && timer !== 120){
        return (
            <button type={"button"} disabled={true} id='service'>
                {timer} sec
            </button>
            );
    }else{
        if(service){
            return (
                <button type={"button"} id='service'
                            className="OnService" onClick={onserviceClicked}>
                En service
            </button>);
        }else{
            return (
                <button type={"button"} id='service'
                        className="OffService" onClick={offserviceClicked}>
                    Hors Service
                </button>

            )

        }
    }



}
export default Service

