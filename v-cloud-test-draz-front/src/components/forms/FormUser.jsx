import React, {useState} from "react"
import {useForm} from "react-hook-form";
import {yupResolver} from "@hookform/resolvers/yup";
import * as yup from "yup"
import {api} from "@/services/api";
import {AppError} from "@/utils/AppError";
import {AUTH_STORAGE} from "@/storage/storageConfig";
import {storageUserSave} from "@/storage/storageUser";
import {SuccessMessage} from "@/components/SuccessMessage";
import {ErrorMessage} from "@/components/ErrorMessage";


export function FormUser({user}) {

    const [successMessage, setSuccessMessage] = useState(false);
    const [errorMessage, setErrorMessage] = useState(false);

    const schema = yup
        .object({
            name: yup.string().required("campo obrigatório").max(191,'Excedido tamanho máximo permitido para este campo'),
            email: yup.string().email("email inválido").required("campo obrigatório").max(191,'Excedido tamanho máximo permitido para este campo'),
        })
        .required()

    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm({
        resolver: yupResolver(schema)
    })

    const handleSubmitForm = async (data) => {
            const token = localStorage.getItem(AUTH_STORAGE);

            try {
                api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                const res = await api.put('/register/update/'+ user.id, data);
                await storageUserSave(res.data.user);
                setSuccessMessage('Perfil atualizado com sucesso!');
                setTimeout(function(){
                    window.location.reload();
                }, 1000);
            } catch (error) {
                const isAppError = error instanceof AppError;
                const errorMsn = isAppError ? error.message : 'Não foi possível atualizar os dados. Tente novamente mais tarde.';
                setErrorMessage(errorMsn);
            }

    }


    return (
        <>

            <div className="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
                <SuccessMessage successMessage={successMessage}/>
                <ErrorMessage errorMessage={errorMessage} />
                <div className="px-4 sm:px-0">
                    <h2 className="text-base font-semibold leading-7 text-gray-900">Informações Pessoais </h2>
                    <p className="mt-1 text-sm leading-6 text-gray-600">Nossa comunicação acontece principalmente por email.</p>
                </div>

                <form onSubmit={handleSubmit(handleSubmitForm)} className="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                    <div className="px-4 py-6 sm:p-8">
                        <div className="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div className="sm:col-span-4">
                                <label htmlFor="name" className="block text-sm font-medium leading-6 text-gray-900">
                                     Nome
                                </label>
                                <div className="mt-2">
                                    <input
                                        defaultValue={user.name}
                                        {...register("name")}
                                        type="text"
                                        id="name"
                                        autoComplete="given-name"
                                        className="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <span className="inline-flex text-sm text-red-700">{errors.name?.message}</span>
                                </div>
                            </div>

                            <div className="sm:col-span-4">
                                <label htmlFor="email" className="block text-sm font-medium leading-6 text-gray-900">
                                    Email
                                </label>
                                <div className="mt-2">
                                    <input
                                        defaultValue={user.email}
                                        {...register("email")}
                                        type="text"
                                        id="email"
                                        autoComplete="given-email"
                                        className="px-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <span className="inline-flex text-sm text-red-700">{errors.email?.message}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div className="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">

                        <button
                            type="submit"
                            className="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Salvar
                        </button>
                    </div>
                </form>
            </div>

        </>
    );
}