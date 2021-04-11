import React, {useState} from 'react';

const Notification = (props) => {
    const [exit, setExit] = useState(false);
    const [width, setWidth] = useState(100);
    const [intervalID, setIntervalID] = useState(null);

    const handleStartTimer = () =>{
        const id = setInterval(()=>{
            setWidth((prev)=>{
                if(prev > 0){
                    return prev - 0.5;
                }
                clearInterval(id)
                return  prev
            })
        }, 20);
        setIntervalID(id);
    }

    const handlePauseTimer = () => {
        clearInterval(intervalID)
    }

    React.useEffect(()=> {
        handleStartTimer()
    }, [])

    const handleCloseNotifications = () => {
        handlePauseTimer();
        setExit(true)

        setTimeout(()=>{
            props.dispatch({
                type: 'REMOVE_NOTIFICATION',
                id: props.id,
            })
        },400)
    }

    React.useEffect(()=> {
        if(width === 0){
            handleCloseNotifications()
        }
    }, [width])

    return (
        <div
            onMouseEnter={handlePauseTimer}
            onMouseLeave={handleStartTimer}
            className={'notification-item ' + props.type + (exit ? ' exit' : '')}>

            <p>{props.message}</p>
            <div className={'bar'}>
                <div className={'bar--filler'} style={{width: width+ '%'}}/>
            </div>
        </div>
    )
}

export default Notification;
