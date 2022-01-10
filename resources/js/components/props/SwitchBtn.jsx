import React from 'react';

function SwitchBtn(props) {

    return (
        <div className={"onoffswitch"}>
        <input type={"checkbox"} className={"onoffswitch-checkbox"} id={"myonoffswitch_" + props.number} tabIndex={"0"} checked={props.checked} onChange={props.callback}/>
           <label className={"onoffswitch-label"} htmlFor={"myonoffswitch_" + props.number}>
               <span className={"onoffswitch-inner"}></span>
               <span className={"onoffswitch-switch"}></span>
           </label>
       </div>
    )
}

export default SwitchBtn;
