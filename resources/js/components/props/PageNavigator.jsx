import React from 'react';

function PageNavigator(props) {

    //Param Only pagination req
    return (
        <div className={'PageNavigator'}>
            <button onClick={props.prev} disabled={(props.prevDisabled)}>
                <img src={'/assets/images/left-arrow.png'} alt={''} className={'navigator-btn'}/>
            </button>
            <button onClick={props.next} disabled={(props.nextDisabled)}>
                <img src={'/assets/images/right-arrow.png'} alt={''} className={'navigator-btn'}/>
            </button>
        </div>
    )
}

export default PageNavigator;
