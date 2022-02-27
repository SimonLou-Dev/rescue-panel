import React, {useContext} from 'react';
import axios from "axios";
import userContext from "../context/UserContext";

function ServiceNav(props) {
    const user = useContext(userContext);

    return (<div className={'ServiceNav'}>

        <div className={'ServiceSelector'}>
            <ul>
                {(user.fire || user.medic && user.crossService) &&
                    <li onClick={async () => {
                        await axios({
                            method: 'PATCH',
                            url: '/data/user/service/LSCoFD'
                        }).then(()=>{props.history.push('/dashboard')})
                    }}>
                        <div>
                            <img src={'/assets/images/LSCoFD.png'} alt={""} />
                            <h2>LSCoFD</h2>
                        </div>
                    </li>
                }
                {(user.medic || user.fire && user.crossService) &&
                    <li onClick={async () => {
                        await axios({
                            method: 'PATCH',
                            url: '/data/user/service/SAMS'
                        }).then(()=>{props.history.push('/dashboard')})
                    }}>
                        <div>

                            <img src={'/assets/images/SAMS.png'} alt={""} />
                            <h2>SAMS</h2>
                        </div>
                    </li>
                }
                {(user.moderator && false) &&
                    <li onClick={async () => {
                        await axios({
                            method: 'PATCH',
                            url: '/data/user/service/staff'
                        }).then(()=>{props.history.push('/dashboard')})
                    }}>
                        <div>

                            <img src={'/assets/images/admin.png'} alt={""} />
                            <h2>Staff</h2>
                        </div>
                    </li>
                }
                {(user.dev) &&
                    <li onClick={async () => {
                        await axios({
                            method: 'PATCH',
                            url: '/data/user/service/dev'
                        }).then(()=>{props.history.push('/dashboard')})
                    }}>
                        <div>
                            <img src={'/assets/images/dev.png'} alt={""} />
                            <h2>Dev</h2>
                        </div>
                    </li>
                }

            </ul>

        </div>

    </div> )
}

export default ServiceNav;
