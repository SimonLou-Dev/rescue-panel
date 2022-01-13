import React from 'react';

function ServiceNav(props) {

    return (<div className={'ServiceNav'}>

        <div className={'ServiceSelector'}>
            <ul>
               <li>
                   <div>
                       <h2>LSCoFD</h2>
                       <img src={'/assets/images/LSCoFD.png'} alt={""} />
                   </div>
               </li>
                <li>
                    <div>
                        <h2>OMC</h2>
                        <img src={'/assets/images/OMC.png'} alt={""} />
                    </div>
                </li>
                <li>
                    <div>
                        <h2>Staff</h2>
                        <img src={'/assets/images/admin.png'} alt={""} />
                    </div>
                </li>
                <li>
                    <div>
                        <h2>Dev</h2>
                        <img src={'/assets/images/dev.png'} alt={""} />
                    </div>
                </li>
            </ul>

        </div>

    </div> )
}

export default ServiceNav;
