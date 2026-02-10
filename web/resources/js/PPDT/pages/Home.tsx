import React from 'react';
import { Head } from '@inertiajs/react';
import PPDTLayout from '../layouts/Layout';

export default function Home() {
    return (
        <PPDTLayout>
            <Head title="Home" />
            <div className="px-4 py-6 sm:px-0">
                <div className="border-4 border-dashed border-gray-200 rounded-lg h-96 flex items-center justify-center">
                    <div className="text-center">
                        <h2 className="text-2xl font-semibold text-gray-700">Welcome to Dar Al-Tauhid</h2>
                        <p className="mt-2 text-gray-500">PPDT Domain is now active and configured.</p>
                    </div>
                </div>
            </div>
        </PPDTLayout>
    );
}
