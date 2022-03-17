import React from 'react';

function UpdaterBtn(props) {

    return (
        <button className={'btn updater'} onClick={()=>props.callback()}>
            <img alt={""} src={'/assets/images/update.png'}/>
        </button>
    )
}

export default UpdaterBtn;
